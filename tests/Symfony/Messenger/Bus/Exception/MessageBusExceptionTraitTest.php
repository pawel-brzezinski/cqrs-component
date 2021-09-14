<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Symfony\Messenger\Bus\Exception;

use Exception;
use PB\Component\CQRS\Tests\Symfony\Messenger\Bus\Fake\FakeMessageBus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class MessageBusExceptionTraitTest extends TestCase
{
    ##############################################
    # MessageBusExceptionTrait::throwException() #
    ##############################################

    /**
     * @return array
     */
    public function throwExceptionDataProvider(): array
    {
        $envelope = new Envelope(new \stdClass(), []);

        // Dataset 1
        $exception1e1 = new Exception('Exception 101', 101);
        $exception1e2 = new Exception('Exception 102', 102);
        $handlerException1 = new HandlerFailedException($envelope, [$exception1e1, $exception1e2]);
        $expected1 = $exception1e1;

        // Dataset 2
        $exception2e1e1 = new Exception('Exception 201', 201);
        $exception2e1e2 = new Exception('Exception 202', 202);
        $exception2e1 = new HandlerFailedException($envelope, [$exception2e1e1, $exception2e1e2]);
        $exception2e2e1 = new Exception('Exception 203', 203);
        $exception2e2e2 = new Exception('Exception 204', 204);
        $exception2e2 = new HandlerFailedException($envelope, [$exception2e2e1, $exception2e2e2]);
        $handlerException2 = new HandlerFailedException($envelope, [$exception2e1, $exception2e2]);
        $expected2 = $exception2e1e1;

        return [
            'handler exception contain only non-handler exceptions' => [$handlerException1, $expected1],
            'handler exception contain other handler exceptions' => [$handlerException2, $expected2],
        ];
    }

    /**
     * @dataProvider throwExceptionDataProvider
     *
     * @param HandlerFailedException $handlerException
     * @param Exception $expected
     *
     * @throws Throwable
     */
    public function testShouldCallThrowExceptionTraitMethodAndCheckIfCorrectExceptionHasBeenThrown(
        HandlerFailedException $handlerException,
        Exception $expected
    ): void {
        // Given

        // When && Then
        try {
            FakeMessageBus::throwMessageBusException($handlerException);
        } catch (Exception $exception) {
            $this->assertSame($expected, $exception);
        }
    }

    #######
    # End #
    #######
}
