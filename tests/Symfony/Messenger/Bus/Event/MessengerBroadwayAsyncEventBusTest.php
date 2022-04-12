<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Symfony\Messenger\Bus\Event;

use Assert\AssertionFailedException;
use Broadway\Domain\{DomainMessage, Metadata};
use Exception;
use PB\Component\CQRS\Symfony\Messenger\Bus\Event\MessengerBroadwayAsyncEventBus;
use PB\Component\CQRS\Tests\Symfony\Messenger\Bus\Fake\FakeClass;
use PB\Component\CQRS\Tests\TestComponent\Messenger\EnvelopeTestTrait;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\{MethodProphecy, ObjectProphecy};
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class MessengerBroadwayAsyncEventBusTest extends TestCase
{
    use EnvelopeTestTrait;
    use ProphecyTrait;

    /** @var ObjectProphecy|MessageBusInterface|null */
    private $messageBusInterface;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->messageBusInterface = $this->prophesize(MessageBusInterface::class);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        $this->messageBusInterface = null;
    }

    ######################################
    # MessengerBusCommand::__construct() #
    ######################################

    /**
     * @return void
     * 
     * @throws AssertionFailedException
     */
    public function testShouldCreateEventBusWithNotSupportedTransportTypeAndCheckIfExceptionHasBeenThrown(): void
    {
        // Expect
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('Transport type `foobar` is not supported. Supported transport types: amqp, doctrine.');

        // When
        $this->createBus('foobar');
    }

    #######
    # End #
    #######

    #################################
    # MessengerBusCommand::handle() #
    #################################

    /**
     * @return array
     */
    public function handleDataProvider(): array
    {
        // Dataset 1
        $transportType1 = null;
        $commandId1 = 'id-1';
        $commandPayload1 = new FakeClass();
        $commandPlayhead1 = 11;
        $commandMetadata1 = new Metadata(['meta-1']);
        $command1 = DomainMessage::recordNow($commandId1, $commandPlayhead1, $commandMetadata1, $commandPayload1);
        $expectedStamps1 = [(new AmqpStamp('PB.Component.CQRS.Tests.Symfony.Messenger.Bus.Fake.FakeClass'))];
        $expectedDispatchResult1 = $this->generateMessageHandlerEnvelopeWithNoStamps($command1);
        $expectedException1 = null;

        // Dataset 2
        $transportType2 = 'amqp';
        $commandId2 = 'id-2';
        $commandPayload2 = new FakeClass();
        $commandPlayhead2 = 22;
        $commandMetadata2 = new Metadata(['meta-2']);
        $command2 = DomainMessage::recordNow($commandId2, $commandPlayhead2, $commandMetadata2, $commandPayload2);
        $expectedStamps2 = [(new AmqpStamp('PB.Component.CQRS.Tests.Symfony.Messenger.Bus.Fake.FakeClass'))];
        $expectedEnvelope2 = $this->generateMessageHandlerEnvelopeWithNoStamps($command2);
        $expectedSubException2 = new Exception('Some test exception message');
        $expectedDispatchResult2 = new HandlerFailedException($expectedEnvelope2, [$expectedSubException2]);
        $expectedException2 = $expectedSubException2;

        // Dataset 3
        $transportType3 = 'doctrine';
        $commandId3 = 'id-3';
        $commandPayload3 = new FakeClass();
        $commandPlayhead3 = 33;
        $commandMetadata3 = new Metadata(['meta-3']);
        $command3 = DomainMessage::recordNow($commandId3, $commandPlayhead3, $commandMetadata3, $commandPayload3);
        $expectedStamps3 = [];
        $expectedDispatchResult3 = $this->generateMessageHandlerEnvelopeWithNoStamps($command3);
        $expectedException3 = null;

        return [
            'transport type is `null` and dispatch command not throw an exception' => [
                $transportType1, $command1, $expectedStamps1, $expectedDispatchResult1, $expectedException1,
            ],
            'transport type is `amqp` and dispatch command throw an exception' => [
                $transportType2, $command2, $expectedStamps2, $expectedDispatchResult2, $expectedException2,
            ],
            'transport type is `doctrine` and dispatch command not throw an exception' => [
                $transportType3, $command3, $expectedStamps3, $expectedDispatchResult3, $expectedException3,
            ],
        ];
    }

    /**
     * @dataProvider handleDataProvider
     *
     * @param string|null $transportType
     * @param DomainMessage $command
     * @param StampInterface[] $expectedStamps
     * @param Envelope|Exception $expectedDispatchResult
     * @param Exception|null $expectedException
     *
     * @throws Throwable
     */
    public function testShouldCallHandleMethodAndCheckIfCommandHasBeenDispatchedCorrectly(
        ?string $transportType,
        DomainMessage $command,
        array $expectedStamps,
        $expectedDispatchResult,
        ?Exception $expectedException
    ): void {
        // Expect
        if (null !== $expectedException) {
            $this->expectException(get_class($expectedException));
            $this->expectExceptionMessage($expectedException->getMessage());
        }

        // Given
        // Mock MessageBusInterface::dispatch()
        /** @var MethodProphecy $methodProp */
        $methodProp = $this->messageBusInterface->dispatch($command, $expectedStamps);
        $methodProp->shouldBeCalledOnce();

        if (false === $expectedDispatchResult instanceof Exception) {
            $methodProp->willReturn($expectedDispatchResult);
        } else {
            $methodProp->willThrow($expectedDispatchResult);
        }

        // When & Then
        try {
            $this->createBus($transportType)->handle($command);
        } catch (Exception $exception) {
            $this->assertSame($expectedException, $exception);

            throw $exception;
        }
    }

    #######
    # End #
    #######

    /**
     * @param string|null $transportType
     *
     * @return MessengerBroadwayAsyncEventBus
     * 
     * @throws AssertionFailedException
     */
    private function createBus(?string $transportType = null): MessengerBroadwayAsyncEventBus
    {
        if (null !== $transportType) {
            return new MessengerBroadwayAsyncEventBus($this->messageBusInterface->reveal(), $transportType);
        }

        return new MessengerBroadwayAsyncEventBus($this->messageBusInterface->reveal());
    }
}
