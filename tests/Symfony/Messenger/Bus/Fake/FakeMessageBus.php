<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Symfony\Messenger\Bus\Fake;

use PB\Component\CQRS\Symfony\Messenger\Bus\Exception\MessageBusExceptionTrait;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class FakeMessageBus
{
    use MessageBusExceptionTrait;

    /**
     * @param HandlerFailedException $handlerFailedException
     *
     * @throws Throwable
     */
    public static function throwMessageBusException(HandlerFailedException $handlerFailedException): void
    {
        (new self())->throwException($handlerFailedException);
    }
}
