<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Symfony\EventSubscriber\EventPublisher;

use Broadway\Domain\DomainMessage;
use Broadway\EventHandling\EventListener;
use PB\Component\CQRS\Symfony\Messenger\Bus\Event\MessengerBroadwayAsyncEventBus;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class AsyncBroadwayEventPublisher implements EventSubscriberInterface, EventListener
{
    const PUBLISH_METHOD = 'publish';

    private MessengerBroadwayAsyncEventBus $eventBus;

    /** @var array DomainMessage[] */
    private array $messages = [];

    /**
     * @param MessengerBroadwayAsyncEventBus $eventBus
     */
    public function __construct(MessengerBroadwayAsyncEventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::TERMINATE => self::PUBLISH_METHOD,
            KernelEvents::TERMINATE => self::PUBLISH_METHOD,
        ];
    }

    /**
     * @param DomainMessage $domainMessage
     */
    public function handle(DomainMessage $domainMessage): void
    {
        $this->messages[] = $domainMessage;
    }

    /**
     * @throws Throwable
     */
    public function publish(): void
    {
        foreach ($this->messages as $message) {
            $this->eventBus->handle($message);
        }
    }
}
