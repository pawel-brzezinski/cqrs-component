<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Helper;

use PB\Component\CQRS\Helper\ReflectionHelper;
use PB\Component\CQRS\Tests\Helper\Fake\{DolorClass, FakeClass};
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class ReflectionHelperTest extends TestCase
{
    ##############################
    # ReflectionHelper::create() #
    ##############################

    /**
     * @throws ReflectionException
     */
    public function testShouldCallCreateMethodAndCheckIfReflectionForGivenObjectHasBeenCreated(): void
    {
        // When
        $actual = ReflectionHelper::create(FakeClass::class);

        // Then
        $this->assertInstanceOf(ReflectionClass::class, $actual);
        $this->assertSame(FakeClass::class, $actual->getName());
    }

    #######
    # End #
    #######

    ###################################
    # ReflectionHelper::constructor() #
    ###################################

    /**
     * @throws ReflectionException
     */
    public function testShouldCallConstructorMethodAndCheckIfReflectionConstructorMethodForGivenObjectHasBeenReturned(): void
    {
        // When
        $actual = ReflectionHelper::constructor(FakeClass::class);

        // Then
        $this->assertNull($actual);
    }

    #######
    # End #
    #######

    ##############################
    # ReflectionHelper::method() #
    ##############################

    /**
     * @throws ReflectionException
     */
    public function testShouldCallMethodAndCheckIfReflectionMethodForGivenObjectHasBeenReturned(): void
    {
        // When
        $actual = ReflectionHelper::method(FakeClass::class, 'lorem');

        // Then
        $this->assertInstanceOf(ReflectionMethod::class, $actual);
        $this->assertSame('lorem', $actual->getName());
    }

    #######
    # End #
    #######

    ################################
    # ReflectionHelper::property() #
    ################################

    /**
     * @throws ReflectionException
     */
    public function testShouldCallPropertyMethodAndCheckIfReflectionPropertyForGivenObjectHasBeenReturned(): void
    {
        // When
        $actual = ReflectionHelper::property(FakeClass::class, 'lorem');

        // Then
        $this->assertInstanceOf(ReflectionProperty::class, $actual);
        $this->assertSame('lorem', $actual->getName());
    }

    #######
    # End #
    #######

    ##################################
    # ReflectionHelper::callMethod() #
    ##################################

    /**
     * @return array
     */
    public function callMethodDataProvider(): array
    {
        $object = new FakeClass();

        // Dataset 1
        $method1 = 'lorem';
        $args1 = [];
        $expected1 = 'lorem';

        // Dataset 2
        $method2 = 'setLorem';
        $args2 = ['lorem new'];
        $expected2 = 'lorem new';

        // Dataset 3
        $method3 = 'ipsum';
        $args3 = [];
        $expected3 = 'ipsum';

        // Dataset 4
        $method4 = 'setIpsum';
        $args4 = ['ipsum new'];
        $expected4 = 'ipsum new';

        // Dataset 5
        $method5 = 'dolor';
        $args5 = [];
        $expected5 = 'dolor';

        // Dataset 6
        $method6 = 'setDolor';
        $args6 = ['dolor new'];
        $expected6 = null;

        return [
            'call method which is public and does not have any arguments but return some value' => [$object, $method1, $args1, $expected1],
            'call method which is public and have arguments and return some value' => [$object, $method2, $args2, $expected2],
            'call method which is protected and does not have any arguments but return some value' => [$object, $method3, $args3, $expected3],
            'call method which is protected and have arguments and return some value' => [$object, $method4, $args4, $expected4],
            'call method which is private and does not have any arguments but return some value' => [$object, $method5, $args5, $expected5],
            'call method which is private and have arguments and does not return value' => [$object, $method6, $args6, $expected6],
        ];
    }

    /**
     * @dataProvider callMethodDataProvider
     *
     * @param object $object
     * @param string $method
     * @param array $args
     * @param mixed $expected
     *
     * @throws ReflectionException
     */
    public function testShouldCallCallMethodAndCheckIfClassMethodHasBeenCalled(
        object $object,
        string $method,
        array $args,
        $expected
    ): void {
        // Expect


        // Given

        // When
        $actual = ReflectionHelper::callMethod($object, $method, $args);

        // Then
        $this->assertSame($expected, $actual);
    }

    #######
    # End #
    #######

    ########################################
    # ReflectionHelper::callStaticMethod() #
    ########################################

    /**
     * @throws ReflectionException
     */
    public function testShouldCallCallStaticMethodAndCheckIfClassStaticMethodHasBeenCalled(): void
    {
        // Expect


        // Given
        $class = FakeClass::class;
        $method = 'mixDolor';
        $args = ['arg 1', 'arg 2'];

        // When
        $actual = ReflectionHelper::callStaticMethod($class, $method, $args);

        // Then
        $this->assertSame('arg 1|arg 2', $actual);
    }

    #######
    # End #
    #######

    ########################################
    # ReflectionHelper::getPropertyValue() #
    ########################################

    /**
     * @throws ReflectionException
     */
    public function testShouldCallGetPropertyValueMethodAndCheckIfClassPropertyValueHasBeenReturned(): void
    {
        // Expect


        // Given
        $object = new FakeClass();
        $property = 'ipsum';

        // When
        $actual = ReflectionHelper::getPropertyValue($object, $property);

        // Then
        $this->assertSame('ipsum', $actual);
    }

    #######
    # End #
    #######

    ##############################################
    # ReflectionHelper::getStaticPropertyValue() #
    ##############################################

    /**
     * @throws ReflectionException
     */
    public function testShouldCallGetStaticPropertyValueMethodAndCheckIfClassStaticMethodHasBeenCalled(): void
    {
        // Expect


        // Given
        $class = FakeClass::class;
        $property = 'lid';

        // When
        $actual = ReflectionHelper::getStaticPropertyValue($class, $property);

        // Then
        $this->assertSame('Lorem Ipsum Dolor', $actual);
    }

    #######
    # End #
    #######

    ########################################
    # ReflectionHelper::setPropertyValue() #
    ########################################

    /**
     * @throws ReflectionException
     */
    public function testShouldCallSetPropertyValueMethodAndCheckIfClassPropertyValueHasBeenChanged(): void
    {
        // Expect


        // Given
        $object = new FakeClass();
        $property = 'ipsum';
        $newValue = 'new ipsum';

        // When & Then
        $this->assertSame('ipsum', ReflectionHelper::getPropertyValue($object, $property));

        ReflectionHelper::setPropertyValue($object, $property, $newValue);

        $this->assertSame($newValue, ReflectionHelper::getPropertyValue($object, $property));
    }

    #######
    # End #
    #######

    ##############################################
    # ReflectionHelper::setStaticPropertyValue() #
    ##############################################

    /**
     * @throws ReflectionException
     */
    public function testShouldCallSetStaticPropertyValueMethodAndCheckIfClassPropertyValueHasBeenChanged(): void
    {
        // Expect


        // Given
        $class = FakeClass::class;
        $property = 'lid';
        $newValue = 'Dolor Ipsum Lorem';

        // When & Then
        $this->assertSame('Lorem Ipsum Dolor', ReflectionHelper::getStaticPropertyValue($class, $property));

        ReflectionHelper::setStaticPropertyValue($class, $property, $newValue);

        $this->assertSame($newValue, ReflectionHelper::getStaticPropertyValue($class, $property));
    }

    #######
    # End #
    #######

    #########################################
    # ReflectionHelper::findPropertyValue() #
    #########################################

    /**
     * @return array
     */
    public function findPropertyValueDataProvider(): array
    {
        // Dataset 1
        $object1 = new DolorClass();
        $property1 = 'value';
        $expected1 = 'value in dolor';

        // Dataset 2
        $object2 = new DolorClass();
        $property2 = 'ipsum';
        $expected2 = 'private ipsum value';

        // Dataset 3
        $object3 = new DolorClass();
        $property3 = 'lorem';
        $expected3 = 'private lorem value';

        // Dataset 4
        $object4 = new DolorClass();
        $property4 = 'foobar';
        $expected4 = null;

        return [
            'property exist directly in object' => [$object1, $property1, $expected1],
            'property exist in nearest parent object' => [$object2, $property2, $expected2],
            'property exist in farthest parent object' => [$object3, $property3, $expected3],
            'property not exist in object and his parent' => [$object4, $property4, $expected4],
        ];
    }

    /**
     * @dataProvider findPropertyValueDataProvider
     *
     * @param object $object
     * @param string $property
     * @param string|null $expected
     *
     * @throws ReflectionException
     */
    public function testShouldCallFindPropertyValueMethodAndCheckIfReturnedValueIsCorrect(
        object $object,
        string $property,
        ?string $expected
    ): void {
        // Expect
        if (null === $expected) {
            $this->expectException(ReflectionException::class);
            $this->expectExceptionMessage('Property `'.$property.'` does not exist in `'.get_class($object).'` object and his parents.');
        }

        // Given


        // When
        $actual = ReflectionHelper::findPropertyValue($object, $property);

        // Then
        if (null !== $expected) {
            $this->assertSame($expected, $actual);
        }
    }

    #######
    # End #
    #######
}
