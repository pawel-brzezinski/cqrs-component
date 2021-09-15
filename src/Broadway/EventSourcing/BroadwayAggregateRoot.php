<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Broadway\EventSourcing;

use Broadway\EventSourcing\EventSourcedAggregateRoot;

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
}
