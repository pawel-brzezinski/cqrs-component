<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Helper;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Static class with common reflection logic.
 *
 * @author Wojciech Brzeziński <wojciech.brzezinski@smartint.pl>
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class ReflectionHelper
{
    /**
     * Returns new reflection of given class.
     *
     * @param string $class
     *
     * @return ReflectionClass
     *
     * @throws ReflectionException
     */
    public static function create(string $class): ReflectionClass
    {
        return new ReflectionClass($class);
    }

    /**
     * Returns constructor method of given class or null if constructor does not exist.
     *
     * @param string $class
     *
     * @return ReflectionMethod|null
     *
     * @throws ReflectionException
     */
    public static function constructor(string $class): ?ReflectionMethod
    {
        return self::create($class)->getConstructor();
    }

    /**
     * Returns method reflection of the given class.
     *
     * @param string $class
     * @param string $name
     *
     * @return ReflectionMethod
     *
     * @throws ReflectionException
     */
    public static function method(string $class, string $name): ReflectionMethod
    {
        return self::create($class)->getMethod($name);
    }

    /**
     * Returns property reflection of the given class.
     *
     * @param string $class
     * @param string $name
     *
     * @return ReflectionProperty
     *
     * @throws ReflectionException
     */
    public static function property(string $class, string $name): ReflectionProperty
    {
        return self::create($class)->getProperty($name);
    }

    /**
     * Calls method of the given object and returns its result.
     *
     * @param object $object
     * @param string $method
     * @param array $args
     *
     * @return mixed
     *
     * @throws ReflectionException
     */
    public static function callMethod(object $object, string $method, array $args)
    {
        $method = self::method(get_class($object), $method);
        $method->setAccessible(true);

        return $method->invoke($object, ...$args);
    }

    /**
     * Calls static method and returns its result.
     *
     * @param string $class
     * @param string $method
     * @param array $args
     *
     * @return mixed
     *
     * @throws ReflectionException
     */
    public static function callStaticMethod(string $class, string $method, array $args)
    {
        $method = self::method($class, $method);
        $method->setAccessible(true);

        return $method->invoke(null, ...$args);
    }

    /**
     * Returns property value of the given object.
     *
     * @param object $object
     * @param string $property
     *
     * @return mixed
     *
     * @throws ReflectionException
     */
    public static function getPropertyValue(object $object, string $property)
    {
        $property = self::property(get_class($object), $property);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * Returns static property value of given object.
     *
     * @param string $class
     * @param string $property
     *
     * @return mixed
     *
     * @throws ReflectionException
     */
    public static function getStaticPropertyValue(string $class, string $property)
    {
        $property = self::property($class, $property);
        $property->setAccessible(true);

        return $property->getValue();
    }

    /**
     * Sets property value of the given object.
     *
     * @param object $object
     * @param string $property
     * @param mixed $value
     *
     * @throws ReflectionException
     */
    public static function setPropertyValue(object $object, string $property, $value): void
    {
        $property = self::property(get_class($object), $property);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }

    /**
     * Sets static property value of the given object.
     *
     * @param string $class
     * @param string $property
     *
     * @param mixed $value
     *
     * @throws ReflectionException
     */
    public static function setStaticPropertyValue(string $class, string $property, $value): void
    {
        $property = self::property($class, $property);
        $property->setAccessible(true);

        $property->setValue(null, $value);
    }

    /**
     * Returns property value of given object also if exist in parent.
     *
     * @param object $object
     * @param string $property
     *
     * @return mixed
     *
     * @throws ReflectionException
     */
    public static function findPropertyValue(object $object, string $property)
    {
        $objClass = get_class($object);

        while (null !== $objClass) {
            $objReflection = self::create($objClass);

            if (false === $objReflection->hasProperty($property)) {
                if (false === $objClass = $objReflection->getParentClass()) {
                    break;
                }

                $objClass = $objClass->getName();
                continue;
            }

            $objProperty = $objReflection->getProperty($property);
            $objProperty->setAccessible(true);

            return $objProperty->getValue($object);
        }

        throw new ReflectionException(sprintf(
            'Property `%s` does not exist in `%s` object and his parents.',
            $property,
            get_class($object)
        ));
    }
}
