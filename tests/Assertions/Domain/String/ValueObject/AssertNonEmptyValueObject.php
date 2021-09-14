<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Assertions\Domain\String\ValueObject;

use PB\Component\CQRS\Domain\String\ValueObject\NonEmpty;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait AssertNonEmptyValueObject
{
    /**
     * Asserts NonEmpty value object.
     *
     * @param NonEmpty $expected
     * @param NonEmpty $actual
     */
    public function assertNonEmptyValueObject(NonEmpty $expected, NonEmpty $actual): void
    {
        self::assertSame((string) $expected, (string) $actual, 'NonEmpty objects are not the same.');
    }

    /**
     * Asserts NonEmpty value object where null is allowed.
     *
     * @param NonEmpty|null $expected
     * @param NonEmpty|null $actual
     */
    public function assertNonEmptyValueObjectOrNull(?NonEmpty $expected, ?NonEmpty $actual): void
    {
        if (null === $expected) {
            self::assertNull($actual, 'NonEmpty object should be null.');
            return;
        }

        self::assertNotNull($actual, 'NonEmpty object should not be null.');
        $this->assertNonEmptyValueObject($expected, $actual);
    }
}
