<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Assertions;

use PB\Component\CQRS\Helper\ReflectionHelper;
use ReflectionException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait AssertObjectConstructor
{
    /**
     * Asserts if class has defined constructor method.
     *
     * @param string $class
     *
     * @throws ReflectionException
     */
    public function assertConstructorExist(string $class): void
    {
        self::assertNotNull(
            ReflectionHelper::constructor($class),
            sprintf('Object %s does not have constructor.', $class)
        );
    }

    /**
     * Asserts if class has not public constructor method.
     *
     * @param string $class
     *
     * @throws ReflectionException
     */
    public function assertConstructorIsNotPublic(string $class): void
    {
        self::assertConstructorExist($class);
        self::assertFalse(
            ReflectionHelper::constructor($class)->isPublic(),
            sprintf('Object %s does have public constructor.', $class)
        );
    }
}
