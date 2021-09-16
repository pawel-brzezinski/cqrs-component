<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Symfony\Messenger\Bus\Exception;

use Exception;
use PB\Component\CQRS\Symfony\Messenger\Bus\Exception\MessageBusException;
use PB\Component\FirstAid\Reflection\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class MessageBusExceptionTest extends TestCase
{
    #######################################
    #  MessageBusException::__construct() #
    #######################################

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
        $expectedPrevious1 = $exception1e1;

        ReflectionHelper::setPropertyValue($handlerException1, 'code', 1);

        // Dataset 2
        $exception2e1e1 = new Exception('Exception 201', 201);
        $exception2e1e2 = new Exception('Exception 202', 202);
        $exception2e1 = new HandlerFailedException($envelope, [$exception2e1e1, $exception2e1e2]);
        $exception2e2e1 = new Exception('Exception 203', 203);
        $exception2e2e2 = new Exception('Exception 204', 204);
        $exception2e2 = new HandlerFailedException($envelope, [$exception2e2e1, $exception2e2e2]);
        $handlerException2 = new HandlerFailedException($envelope, [$exception2e1, $exception2e2]);
        $expectedPrevious2 = $exception2e1e1;

        ReflectionHelper::setPropertyValue($handlerException2, 'code', 2);

        return [
            'handler exception contain only non-handler exceptions' => [$handlerException1, $expectedPrevious1],
            'handler exception contain other handler exceptions' => [$handlerException2, $expectedPrevious2],
        ];
    }

    /**
     * @dataProvider throwExceptionDataProvider
     *
     * @param HandlerFailedException $handlerException
     * @param Exception $expectedPrevious
     *
     * @throws Throwable
     */
    public function testShouldThrowExceptionAndCheckIfThisExceptionIsCorrect(
        HandlerFailedException $handlerException,
        Exception $expectedPrevious
    ): void {
        // Expect
        $this->expectException(MessageBusException::class);
        $this->expectExceptionMessage($handlerException->getMessage());
        $this->expectExceptionCode($handlerException->getCode());

        // Given

        // When && Then
        try {
            throw new MessageBusException($handlerException);
        } catch (Exception $exception) {
            $this->assertSame($expectedPrevious, $exception->getPrevious());
            
            throw $exception;
        }
    }

    #######
    # End #
    #######
}
