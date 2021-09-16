<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Symfony\Messenger\Bus\Exception;

use \Exception;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class MessageBusException extends Exception
{
    public function __construct(HandlerFailedException $exception)
    {
        $message = $exception->getMessage();
        $code = $exception->getCode();

        while ($exception instanceof HandlerFailedException) {
            $exception = $exception->getPrevious();
        }

        parent::__construct($message, $code, $exception);
    }
}
