<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Symfony\Messenger\Bus\Query;

use PB\Component\CQRS\Query\{QueryBusInterface, QueryInterface};
use PB\Component\CQRS\Symfony\Messenger\Bus\Exception\MessageBusExceptionTrait;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class MessengerBusQuery implements QueryBusInterface
{
    use MessageBusExceptionTrait;

    private MessageBusInterface $messageBus;

    /**
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @param QueryInterface $query
     *
     * @return mixed
     *
     * @throws Throwable
     */
    public function handle(QueryInterface $query)
    {
        try {
            $envelope = $this->messageBus->dispatch($query);

            /** @var HandledStamp $stamp */
            $stamp = $envelope->last(HandledStamp::class);
        } catch (HandlerFailedException $exception) {
            $this->throwException($exception);
        }

        /** @noinspection PhpUndefinedVariableInspection */
        return $stamp->getResult(); /** @phpstan-ignore-line */
    }
}
