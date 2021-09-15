<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Assertions\External\Ramsey\Uuid;

use Ramsey\Uuid\UuidInterface;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait AssertUuidObject
{
    /**
     * Asserts Uuid object.
     *
     * @param UuidInterface $expected
     * @param UuidInterface $actual
     */
    public function assertUuidObject(UuidInterface $expected, UuidInterface $actual): void
    {
        self::assertTrue($expected->equals($actual), 'Uuid objects are not the same.');
    }
}
