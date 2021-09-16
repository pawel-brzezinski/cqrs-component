<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Broadway\EventSourcing\Exception;

use Exception;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class AggregateRootMarkedAsDeleted extends Exception
{
    private const MESSAGE = 'Aggregate root `%s` cannot be modified because is marked as deleted.';

    /**
     * @param string $aggregateRootId
     */
    public function __construct(string $aggregateRootId)
    {
        parent::__construct(sprintf(self::MESSAGE, $aggregateRootId));
    }
}
