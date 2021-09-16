<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Symfony\Controller\Fake;

use PB\Component\CQRS\Query\QueryInterface;
use PB\Component\CQRS\Symfony\Controller\QueryController;
use Throwable;

/**
 *
 */
final class FakeQueryController extends QueryController
{
    /**
     * @param QueryInterface $query
     *
     * @throws Throwable
     */
    public function callAsk(QueryInterface $query): void
    {
        $this->ask($query);
    }
}
