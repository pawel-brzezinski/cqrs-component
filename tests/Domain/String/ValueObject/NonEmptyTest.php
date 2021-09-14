<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Domain\String\ValueObject;

use Assert\AssertionFailedException;
use PB\Component\CQRS\Domain\String\ValueObject\NonEmpty;
use PB\Component\CQRS\Tests\Assertions\AssertObjectConstructor;
use PB\Component\FirstAid\Reflection\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class NonEmptyTest extends TestCase
{
    use AssertObjectConstructor;

    ###########################
    # NonEmpty::__construct() #
    ###########################

    /**
     * @throws ReflectionException
     */
    public function testShouldCheckIfValueObjectConstructorIsPrivate(): void
    {
        // Then
        $this->assertConstructorIsNotPublic(NonEmpty::class);
    }

    #######
    # End #
    #######

    ##########################
    # NonEmpty::fromString() #
    ##########################

    /**
     * @return array
     */
    public function fromStringDataProvider(): array
    {
        return [
            'valid string' => ['foobar', 'foobar', null],
            'empty string' => ['', null, 'Value cannot be empty.'],
        ];
    }

    /**
     * @dataProvider fromStringDataProvider
     *
     * @param string $value
     * @param string|null $expected
     * @param string|null $expectedExceptionMsg
     *
     * @throws AssertionFailedException
     * @throws ReflectionException
     */
    public function testShouldCallFromStringStaticMethodAndCheckIfValueObjectHasBeenCreatedCorrectly(
        string $value,
        ?string $expected,
        ?string $expectedExceptionMsg
    ): void {
        // Expect
        if (null === $expected) {
            $this->expectException(AssertionFailedException::class);
            $this->expectExceptionMessage($expectedExceptionMsg);
        }

        // Given


        // When
        $actual = NonEmpty::fromString($value);

        // Then
        if (null !== $expected) {
            $this->assertInstanceOf(NonEmpty::class, $actual);
            $this->assertSame($expected, ReflectionHelper::getPropertyValue($actual, 'value'));
        }
    }

    #######
    # End #
    #######

    ######################
    # NonEmpty::equals() #
    ######################

    /**
     * @return array
     */
    public function equalsDataProvider(): array
    {
        return [
            'values are equals' => ['foobar', 'foobar', true],
            'values are not equals' => ['lorem', 'ipsum', false],
        ];
    }

    /**
     * @dataProvider equalsDataProvider
     *
     * @param string $value
     * @param string $target
     * @param bool $expected
     *
     * @throws AssertionFailedException
     */
    public function testShouldCallEqualsMethodAndCheckIfReturnedFlagIsCorrect(
        string $value,
        string $target,
        bool $expected
    ): void {
        // Given
        $value = NonEmpty::fromString($value);
        $target = NonEmpty::fromString($target);

        // When
        $actual = $value->equals($target);

        // Then
        $this->assertSame($expected, $actual);
    }

    #######
    # End #
    #######

    ########################
    # NonEmpty::toString() #
    ########################

    /**
     * @throws AssertionFailedException
     */
    public function testShouldCallToStringMethodAndCheckIfReturnedValueIsCorrect(): void
    {
        // Given
        $value = 'Lorem Ipsum';

        // When
        $actual = NonEmpty::fromString($value)->toString();

        // Then
        $this->assertSame($value, $actual);
    }

    #######
    # End #
    #######

    ##########################
    # NonEmpty::__toString() #
    ##########################

    /**
     * @throws AssertionFailedException
     */
    public function testShouldCallToStringMagicMethodAndCheckIfReturnedValueIsCorrect(): void
    {
        // Given
        $value = 'Dolor Sit';

        // When
        $actual = (string) NonEmpty::fromString($value);

        // Then
        $this->assertSame($value, $actual);
    }

    #######
    # End #
    #######
}
