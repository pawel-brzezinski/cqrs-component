<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Assertions\Domain\Number\ValueObject;

use PB\Component\CQRS\Domain\Number\ValueObject\NonNegativeFloat;
use PB\Component\CQRS\Tests\Assertions\PHPUnitAssertionTrait;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait AssertNonNegativeFloatValueObject
{
    use PHPUnitAssertionTrait;

    /**
     * @param NonNegativeFloat $expected
     * @param NonNegativeFloat $actual
     *
     * @return void
     */
    public function assertNonNegativeFloatValueObject(NonNegativeFloat $expected, NonNegativeFloat $actual): void
    {
        self::assertSame($expected->dump(), $actual->dump());
    }

    /**
     * @param NonNegativeFloat|null $expected
     * @param NonNegativeFloat|null $actual
     *
     * @return void
     */
    public function assertNonNegativeFloatValueObjectOrNull(?NonNegativeFloat $expected, ?NonNegativeFloat $actual): void
    {
        if (null === $expected) {
            self::assertNull($actual, 'NonNegativeFloat object should be null.');
            return;
        }

        self::assertNotNull($actual, 'NonNegativeFloat object should not be null.');
        $this->assertNonNegativeFloatValueObject($expected, $actual);
    }
}
