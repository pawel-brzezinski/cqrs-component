<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Broadway\Repository;

use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\{EventSourcingRepository, EventStreamDecorator};
use Broadway\EventStore\EventStore;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class BroadwayStore extends EventSourcingRepository
{
    /**
     * @param EventStore $eventStore
     * @param EventBus $eventBus
     * @param string $aggregateClass
     * @param EventStreamDecorator[] $eventStreamDecorators
     */
    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        string $aggregateClass,
        array $eventStreamDecorators = []
    ) {
        parent::__construct(
            $eventStore,
            $eventBus,
            $aggregateClass,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }
}
