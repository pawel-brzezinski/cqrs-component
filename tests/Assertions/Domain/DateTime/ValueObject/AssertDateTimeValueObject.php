<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Assertions\Domain\DateTime\ValueObject;

use PB\Component\CQRS\Domain\DateTime\ValueObject\DateTime;

/**
 *
 */
trait AssertDateTimeValueObject
{
    /**
     * @param DateTime $expected
     * @param DateTime $actual
     */
    public function assertDateTimeValueObject(DateTime $expected, DateTime $actual): void
    {
        self::assertSame($expected->format('c'), $actual->format('c'), 'DateTime objects are not equals.');
        self::assertSame($expected->format('e'), $actual->format('e'), 'DateTime objects does not have the same timezone.');
    }

    /**
     * @param DateTime|null $expected
     * @param DateTime|null $actual
     */
    public function assertDateTimeValueObjectOrNull(?DateTime $expected, ?DateTime $actual): void
    {
        if (null === $expected) {
            self::assertNull($actual, 'DateTime object should be null.');
            return;
        }

        self::assertNotNull($actual, 'DateTime object should not be null.');
        $this->assertDateTimeValueObject($expected, $actual);
    }
}
