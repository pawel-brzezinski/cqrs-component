<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Query\Fake;

use PB\Component\CQRS\Query\{QueryBusInterface, QueryBusTrait, QueryInterface};
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class FakeQueryBus
{
    use QueryBusTrait;

    /**
     * @param QueryBusInterface $queryBus
     */
    public function __construct(QueryBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @param QueryInterface $query
     * 
     * @return mixed
     *
     * @throws Throwable
     */
    public function callAsk(QueryInterface $query)
    {
        return $this->ask($query);
    }
}
