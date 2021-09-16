<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Symfony\Messenger\Bus\Event;

use Broadway\Domain\DomainMessage;
use PB\Component\CQRS\Broadway\Bus\Event\BroadwayAsyncEventBusInterface;
use PB\Component\CQRS\Symfony\Messenger\Bus\Exception\MessageBusException;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class MessengerBroadwayAsyncEventBus implements BroadwayAsyncEventBusInterface
{
    private MessageBusInterface $messageBus;

    /**
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @param DomainMessage $command
     *
     * @throws Throwable
     */
    public function handle(DomainMessage $command): void
    {
        try {
            $this->messageBus->dispatch($command, [
                new AmqpStamp($command->getType()),
            ]);
        } catch (HandlerFailedException $exception) {
            throw (new MessageBusException($exception))->getPrevious();
        }
    }
}
