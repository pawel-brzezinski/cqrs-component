<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Assertions\Domain\Number\ValueObject;

use PB\Component\CQRS\Domain\Number\ValueObject\NonNegativeInteger;
use PB\Component\CQRS\Tests\Assertions\PHPUnitAssertionTrait;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait AssertNonNegativeIntegerValueObject
{
    use PHPUnitAssertionTrait;

    /**
     * @param NonNegativeInteger $expected
     * @param NonNegativeInteger $actual
     *
     * @return void
     */
    public function assertNonNegativeIntegerValueObject(NonNegativeInteger $expected, NonNegativeInteger $actual): void
    {
        self::assertSame($expected->dump(), $actual->dump());
    }

    /**
     * @param NonNegativeInteger|null $expected
     * @param NonNegativeInteger|null $actual
     *
     * @return void
     */
    public function assertNonNegativeIntegerValueObjectOrNull(?NonNegativeInteger $expected, ?NonNegativeInteger $actual): void
    {
        if (null === $expected) {
            self::assertNull($actual, 'NonNegativeInteger object should be null.');
            return;
        }

        self::assertNotNull($actual, 'NonNegativeInteger object should not be null.');
        $this->assertNonNegativeIntegerValueObject($expected, $actual);
    }
}
