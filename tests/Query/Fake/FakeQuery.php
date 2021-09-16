<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Query\Fake;

use PB\Component\CQRS\Query\QueryInterface;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class FakeQuery implements QueryInterface
{
    public int $id;

    /**
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
