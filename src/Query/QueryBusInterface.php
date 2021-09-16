<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Query;

use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
interface QueryBusInterface
{
    /**
     * @param QueryInterface $query
     *
     * @return mixed
     *
     * @throws Throwable
     */
    public function handle(QueryInterface $query);
}
