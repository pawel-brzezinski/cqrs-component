<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Query;

use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait QueryBusTrait
{
    private QueryBusInterface $queryBus;

    /**
     * @param QueryInterface $query
     *
     * @throws Throwable
     */
    protected function ask(QueryInterface $query): void
    {
        $this->queryBus->handle($query);
    }
}
