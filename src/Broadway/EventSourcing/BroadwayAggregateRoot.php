<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Broadway\EventSourcing;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use PB\Component\CQRS\Broadway\EventSourcing\Exception\AggregateRootMarkedAsDeleted;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class BroadwayAggregateRoot extends EventSourcedAggregateRoot
{
    protected bool $markedAsDeleted = false;

    /**
     * @return bool
     */
    public function isMarkedAsDeleted(): bool
    {
        return $this->markedAsDeleted;
    }

    /**
     * {@inheritDoc}
     *
     * @throws AggregateRootMarkedAsDeleted
     */
    public function apply($event): void
    {
        if (true === $this->isMarkedAsDeleted()) {
            throw new AggregateRootMarkedAsDeleted($this->getAggregateRootId());
        }

        parent::apply($event);
    }
}
