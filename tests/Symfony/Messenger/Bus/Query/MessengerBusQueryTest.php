<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Symfony\Messenger\Bus\Query;

use Exception;
use PB\Component\CQRS\Query\QueryInterface;
use PB\Component\CQRS\Symfony\Messenger\Bus\Query\MessengerBusQuery;
use PB\Component\CQRS\Tests\Query\Fake\FakeQuery;
use PB\Component\CQRS\Tests\TestComponent\Messenger\EnvelopeTestTrait;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class MessengerBusQueryTest extends TestCase
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

    ###############################
    # MessengerBusQuery::handle() #
    ###############################

    /**
     * @return array
     */
    public function handleDataProvider(): array
    {
        // Dataset 1
        $query1 = new FakeQuery(1);
        $handlerResult1 = new stdClass();
        $dispatchResult1 = $this->generateMessageHandlerEnvelopeWithHandledStamp('FakeQueryHandler', $query1, $handlerResult1);
        $expectedResult1 = $handlerResult1;
        $expectedException1 = null;

        // Dataset 2
        $query2 = new FakeQuery(2);
        $envelope2 = $this->generateMessageHandlerEnvelopeWithNoStamps($query2);
        $subException2 = new Exception('Some test exception message');
        $dispatchResult2 = new HandlerFailedException($envelope2, [$subException2]);
        $expectedResult2 = null;
        $expectedException2 = $subException2;

        return [
            'dispatch query not throw an exception' => [$query1, $dispatchResult1, $expectedResult1, $expectedException1],
            'dispatch query throw an exception' => [$query2, $dispatchResult2, $expectedResult2, $expectedException2],
        ];
    }

    /**
     * @dataProvider handleDataProvider
     *
     * @param QueryInterface $query
     * @param Envelope|Exception $dispatchResult
     * @param object|null $expectedResult
     * @param Exception|null $expectedException
     *
     * @throws Throwable
     */
    public function testShouldCallHandleMethodAndCheckIfQueryHasBeenDispatchedCorrectly(
        QueryInterface $query,
        $dispatchResult,
        ?object $expectedResult,
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
        $methodProp = $this->messageBusInterface->dispatch($query);
        $methodProp->shouldBeCalledOnce();

        if (false === $dispatchResult instanceof Exception) {
            $methodProp->willReturn($dispatchResult);
        } else {
            $methodProp->willThrow($dispatchResult);
        }
        // End

        // When & Then
        try {
            $actual = $this->createBus()->handle($query);
        } catch (Exception $exception) {
            $this->assertSame($expectedException, $exception);

            throw $exception;
        }

        if (null === $expectedException) {
            $this->assertSame($expectedResult, $actual);
        }
    }

    #######
    # End #
    #######

    /**
     * @return MessengerBusQuery
     */
    private function createBus(): MessengerBusQuery
    {
        return new MessengerBusQuery($this->messageBusInterface->reveal());
    }
}
