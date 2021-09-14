<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Symfony\Messenger\Bus\Command;

use Exception;
use PB\Component\CQRS\Command\CommandInterface;
use PB\Component\CQRS\Symfony\Messenger\Bus\Command\MessengerBusCommand;
use PB\Component\CQRS\Tests\Command\Fake\FakeCommand;
use PB\Component\CQRS\Tests\TestComponent\Messenger\EnvelopeTestTrait;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class MessengerBusCommandTest extends TestCase
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
        $command1 = new FakeCommand(1);
        $dispatchResult1 = $this->generateMessageHandlerEnvelopeWithNoStamps($command1);
        $expectedException1 = null;

        // Dataset 2
        $command2 = new FakeCommand(2);
        $envelope2 = $this->generateMessageHandlerEnvelopeWithNoStamps($command2);
        $subException2 = new Exception('Some test exception message');
        $dispatchResult2 = new HandlerFailedException($envelope2, [$subException2]);
        $expectedException2 = $subException2;

        return [
            'dispatch command not throw an exception' => [$command1, $dispatchResult1, $expectedException1],
            'dispatch command throw an exception' => [$command2, $dispatchResult2, $expectedException2],
        ];
    }

    /**
     * @dataProvider handleDataProvider
     *
     * @param CommandInterface $command
     * @param Envelope|Exception $dispatchResult
     * @param Exception|null $expectedException
     *
     * @throws Throwable
     */
    public function testShouldCallHandleMethodAndCheckIfCommandHasBeenDispatchedCorrectly(
        CommandInterface $command,
        $dispatchResult,
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
        $methodProp = $this->messageBusInterface->dispatch($command);
        $methodProp->shouldBeCalledOnce();

        if (false === $dispatchResult instanceof Exception) {
            $methodProp->willReturn($dispatchResult);
        } else {
            $methodProp->willThrow($dispatchResult);
        }
        // End

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
     * @return MessengerBusCommand
     */
    private function createBus(): MessengerBusCommand
    {
        return new MessengerBusCommand($this->messageBusInterface->reveal());
    }
}
