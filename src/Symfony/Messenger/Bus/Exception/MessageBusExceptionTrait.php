<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Symfony\Messenger\Bus\Exception;

use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait MessageBusExceptionTrait
{
    /**
     * @param HandlerFailedException $exception
     *
     * @throws Throwable
     */
    private function throwException(HandlerFailedException $exception): void
    {
        while ($exception instanceof HandlerFailedException) {
            $exception = $exception->getPrevious();
        }

        throw $exception;
    }
}
