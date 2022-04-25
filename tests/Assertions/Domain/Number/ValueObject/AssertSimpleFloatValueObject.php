<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Assertions\Domain\Number\ValueObject;

use PB\Component\CQRS\Domain\Number\ValueObject\SimpleFloat;
use PB\Component\CQRS\Tests\Assertions\PHPUnitAssertionTrait;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait AssertSimpleFloatValueObject
{
    use PHPUnitAssertionTrait;

    /**
     * @param SimpleFloat $expected
     * @param SimpleFloat $actual
     *
     * @return void
     */
    public function assertSimpleFloatValueObject(SimpleFloat $expected, SimpleFloat $actual): void
    {
        self::assertSame($expected->dump(), $actual->dump());
    }

    /**
     * @param SimpleFloat|null $expected
     * @param SimpleFloat|null $actual
     *
     * @return void
     */
    public function assertSimpleFloatValueObjectOrNull(?SimpleFloat $expected, ?SimpleFloat $actual): void
    {
        if (null === $expected) {
            self::assertNull($actual, 'SimpleFloat object should be null.');
            return;
        }

        self::assertNotNull($actual, 'SimpleFloat object should not be null.');
        $this->assertSimpleFloatValueObject($expected, $actual);
    }
}
