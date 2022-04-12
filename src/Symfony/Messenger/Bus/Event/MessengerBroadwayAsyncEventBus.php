<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Symfony\Messenger\Bus\Event;

use Assert\Assertion;
use Assert\AssertionFailedException;
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
    private const AMQP_TYPE = 'amqp';
    private const DOCTRINE_TYPE = 'doctrine';

    private const ALLOWED_TYPES = [self::AMQP_TYPE, self::DOCTRINE_TYPE];
    private const NOT_ALLOWED_TYPE_MESSAGE = 'Transport type `%s` is not supported. Supported transport types: %s.';

    private MessageBusInterface $messageBus;

    private string $transportType;

    /**
     * @param MessageBusInterface $messageBus
     * @param string $transportType
     *
     * @throws AssertionFailedException
     */
    public function __construct(MessageBusInterface $messageBus, string $transportType = self::AMQP_TYPE)
    {
        Assertion::inArray($transportType, self::ALLOWED_TYPES, self::NOT_ALLOWED_TYPE_MESSAGE);

        $this->messageBus = $messageBus;
        $this->transportType = $transportType;
    }

    /**
     * @param DomainMessage $command
     *
     * @throws Throwable
     */
    public function handle(DomainMessage $command): void
    {
        try {
            $this->messageBus->dispatch($command, $this->stamps($command));
        } catch (HandlerFailedException $exception) {
            throw (new MessageBusException($exception))->getPrevious();
        }
    }

    /**
     * @param DomainMessage $command
     *
     * @return array
     */
    private function stamps(DomainMessage $command): array
    {
        if (self::AMQP_TYPE === $this->transportType) {
            return [new AmqpStamp($command->getType())];
        }
        
        return [];
    }
}
