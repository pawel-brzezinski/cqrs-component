<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Symfony\Messenger\Bus\Command;

use PB\Component\CQRS\Command\{CommandBusInterface, CommandInterface};
use PB\Component\CQRS\Symfony\Messenger\Bus\Exception\MessageBusException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class MessengerBusCommand implements CommandBusInterface
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
     * @param CommandInterface $command
     *
     * @throws Throwable
     */
    public function handle(CommandInterface $command): void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch (HandlerFailedException $exception) {
            throw (new MessageBusException($exception))->getPrevious();
        }
    }
}
