<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Command\Fake;

use PB\Component\CQRS\Command\CommandInterface;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class FakeCommand implements CommandInterface
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
