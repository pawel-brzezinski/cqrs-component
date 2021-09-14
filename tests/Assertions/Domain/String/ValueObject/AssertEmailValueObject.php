<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Assertions\Domain\String\ValueObject;

use PB\Component\CQRS\Domain\String\ValueObject\Email;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait AssertEmailValueObject
{
    /**
     * Asserts Email value object.
     *
     * @param Email $expected
     * @param Email $actual
     */
    public function assertEmailValueObject(Email $expected, Email $actual): void
    {
        self::assertSame($expected->toString(), $actual->toString(), 'Email objects are not the same.');
    }

    /**
     * Asserts Email value object where null is allowed.
     *
     * @param Email|null $expected
     * @param Email|null $actual
     */
    public function assertEmailValueObjectOrNull(?Email $expected, ?Email $actual): void
    {
        if (null === $expected) {
            self::assertNull($actual, 'Email object should be null.');
            return;
        }

        self::assertNotNull($actual, 'Email object should not be null.');
        $this->assertEmailValueObject($expected, $actual);
    }
}
