<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Assertions\Domain\Number\ValueObject;

use PB\Component\CQRS\Domain\Number\ValueObject\PositiveFloat;
use PB\Component\CQRS\Tests\Assertions\PHPUnitAssertionTrait;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait AssertPositiveFloatValueObject
{
    use PHPUnitAssertionTrait;

    /**
     * @param PositiveFloat $expected
     * @param PositiveFloat $actual
     *
     * @return void
     */
    public function assertPositiveFloatValueObject(PositiveFloat $expected, PositiveFloat $actual): void
    {
        self::assertSame($expected->dump(), $actual->dump());
    }

    /**
     * @param PositiveFloat|null $expected
     * @param PositiveFloat|null $actual
     *
     * @return void
     */
    public function assertPositiveFloatValueObjectOrNull(?PositiveFloat $expected, ?PositiveFloat $actual): void
    {
        if (null === $expected) {
            self::assertNull($actual, 'PositiveFloat object should be null.');
            return;
        }

        self::assertNotNull($actual, 'PositiveFloat object should not be null.');
        $this->assertPositiveFloatValueObject($expected, $actual);
    }
}
