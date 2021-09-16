<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Assertions;

use PB\Component\FirstAid\Reflection\ReflectionHelper;
use ReflectionException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 *
 * @method static void assertNotNull($actual, string $message = '')
 * @method static void assertTrue($condition, string $message = '')
 */
trait AssertObjectMethod
{
    use PHPUnitAssertionTrait;

    /**
     * Asserts if class has defined method.
     *
     * @param string $class
     * @param string $method
     *
     * @throws ReflectionException
     */
    public function assertMethodExist(string $class, string $method): void
    {
        self::assertNotNull(
            ReflectionHelper::method($class, $method),
            sprintf('Object %s does not have method %s.', $class, $method)
        );
    }

    /**
     * Asserts if class method exist and is protected.
     *
     * @param string $class
     * @param string $method
     *
     * @throws ReflectionException
     */
    public function assertMethodIsProtected(string $class, string $method): void
    {
        self::assertMethodExist($class, $method);
        self::assertTrue(
            ReflectionHelper::method($class, $method)->isProtected(),
            sprintf('Method %s::%s() is not protected.', $class, $method)
        );
    }
}
