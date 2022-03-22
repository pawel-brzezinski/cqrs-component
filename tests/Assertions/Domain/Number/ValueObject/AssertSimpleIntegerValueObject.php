<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Assertions\Domain\Number\ValueObject;

use PB\Component\CQRS\Domain\Number\ValueObject\SimpleInteger;
use PB\Component\CQRS\Tests\Assertions\PHPUnitAssertionTrait;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait AssertSimpleIntegerValueObject
{
    use PHPUnitAssertionTrait;

    /**
     * @param SimpleInteger $expected
     * @param SimpleInteger $actual
     *
     * @return void
     */
    public function assertSimpleIntegerValueObject(SimpleInteger $expected, SimpleInteger $actual): void
    {
        self::assertSame($expected->dump(), $actual->dump());
    }

    /**
     * @param SimpleInteger|null $expected
     * @param SimpleInteger|null $actual
     *
     * @return void
     */
    public function assertSimpleIntegerValueObjectOrNull(?SimpleInteger $expected, ?SimpleInteger $actual): void
    {
        if (null === $expected) {
            self::assertNull($actual, 'SimpleInteger object should be null.');
            return;
        }

        self::assertNotNull($actual, 'SimpleInteger object should not be null.');
        $this->assertSimpleIntegerValueObject($expected, $actual);
    }
}
