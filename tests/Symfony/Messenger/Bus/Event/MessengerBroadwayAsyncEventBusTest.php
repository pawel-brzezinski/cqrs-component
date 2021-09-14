<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Symfony\Messenger\Bus\Event;

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

    #################################
    # MessengerBusCommand::handle() #
    #################################

    /**
     * @return array
     */
    public function handleDataProvider(): array
    {
        // Dataset 1
        $commandId1 = 'id-1';
        $commandPayload1 = new FakeClass();
        $commandPlayhead1 = 11;
        $commandMetadata1 = new Metadata(['meta-1']);
        $command1 = DomainMessage::recordNow($commandId1, $commandPlayhead1, $commandMetadata1, $commandPayload1);
        $expectedStamps1 = [(new AmqpStamp('PB.Component.CQRS.Tests.Symfony.Messenger.Bus.Fake.FakeClass'))];
        $expectedDispatchResult1 = $this->generateMessageHandlerEnvelopeWithNoStamps($command1);
        $expectedException1 = null;

        // Dataset 2
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

        return [
            'dispatch command not throw an exception' => [$command1, $expectedStamps1, $expectedDispatchResult1, $expectedException1],
            'dispatch command throw an exception' => [$command2, $expectedStamps2, $expectedDispatchResult2, $expectedException2],
        ];
    }

    /**
     * @dataProvider handleDataProvider
     *
     * @param DomainMessage $command
     * @param StampInterface[] $expectedStamps
     * @param Envelope|Exception $expectedDispatchResult
     * @param Exception|null $expectedException
     *
     * @throws Throwable
     */
    public function testShouldCallHandleMethodAndCheckIfCommandHasBeenDispatchedCorrectly(
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
            $this->createBus()->handle($command);
        } catch (Exception $exception) {
            $this->assertSame($expectedException, $exception);

            throw $exception;
        }
    }

    #######
    # End #
    #######

    /**
     * @return MessengerBroadwayAsyncEventBus
     */
    private function createBus(): MessengerBroadwayAsyncEventBus
    {
        return new MessengerBroadwayAsyncEventBus($this->messageBusInterface->reveal());
    }
}
