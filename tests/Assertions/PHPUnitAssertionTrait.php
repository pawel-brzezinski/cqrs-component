<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Assertions;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait PHPUnitAssertionTrait
{
    /**
     * @param mixed $expected
     * @param mixed $actual
     * @param string $message
     */
    abstract public static function assertSame($expected, $actual, string $message = ''): void;

    /**
     * @param mixed $actual
     * @param string $message
     */
    abstract public static function assertNull($actual, string $message = ''): void;

    /**
     * @param mixed $actual
     * @param string $message
     */
    abstract public static function assertNotNull($actual, string $message = ''): void;

    /**
     * @param mixed $condition
     * @param string $message
     */
    abstract public static function assertTrue($condition, string $message = ''): void;
}
