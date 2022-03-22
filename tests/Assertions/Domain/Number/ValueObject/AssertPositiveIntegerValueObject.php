<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Assertions\Domain\Number\ValueObject;

use PB\Component\CQRS\Domain\Number\ValueObject\PositiveInteger;
use PB\Component\CQRS\Tests\Assertions\PHPUnitAssertionTrait;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait AssertPositiveIntegerValueObject
{
    use PHPUnitAssertionTrait;

    /**
     * @param PositiveInteger $expected
     * @param PositiveInteger $actual
     *
     * @return void
     */
    public function assertPositiveIntegerValueObject(PositiveInteger $expected, PositiveInteger $actual): void
    {
        self::assertSame($expected->dump(), $actual->dump());
    }

    /**
     * @param PositiveInteger|null $expected
     * @param PositiveInteger|null $actual
     *
     * @return void
     */
    public function assertPositiveIntegerValueObjectOrNull(?PositiveInteger $expected, ?PositiveInteger $actual): void
    {
        if (null === $expected) {
            self::assertNull($actual, 'PositiveInteger object should be null.');
            return;
        }

        self::assertNotNull($actual, 'PositiveInteger object should not be null.');
        $this->assertPositiveIntegerValueObject($expected, $actual);
    }
}
