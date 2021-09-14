<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\TestComponent\Messenger;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\{DelayStamp, HandledStamp};

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait EnvelopeTestTrait
{
    /**
     * @param object $message
     *
     * @return Envelope
     */
    private function generateMessageHandlerEnvelopeWithNoStamps(object $message): Envelope
    {
        return new Envelope($message, []);
    }

    /**
     * @param string $messageHandler
     * @param object $message
     * @param mixed $result
     *
     * @return Envelope
     */
    private function generateMessageHandlerEnvelopeWithHandledStamp(string $messageHandler, object $message, $result): Envelope
    {
        $stamp = new HandledStamp($result, $messageHandler.'::__invoke');

        return new Envelope($message, [$stamp]);
    }

    /**
     * @param object $message
     * @param int $delay
     *
     * @return Envelope
     */
    private function generateMessageHandlerEnvelopeWithDelayStamp(object $message, int $delay): Envelope
    {
        $stamp = new DelayStamp($delay);

        return new Envelope($message, [$stamp]);
    }
}
