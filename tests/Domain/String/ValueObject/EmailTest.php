<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Domain\String\ValueObject;

use Assert\AssertionFailedException;
use PB\Component\CQRS\Domain\String\ValueObject\Email;
use PB\Component\CQRS\Helper\ReflectionHelper;
use PB\Component\CQRS\Tests\Assertions\AssertObjectConstructor;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>a
 */
final class EmailTest extends TestCase
{
    use AssertObjectConstructor;

    ########################
    # Email::__construct() #
    ########################

    /**
     * @throws ReflectionException
     */
    public function testShouldCheckIfValueObjectConstructorIsPrivate(): void
    {
        // Then
        $this->assertConstructorIsNotPublic(Email::class);
    }

    #######
    # End #
    #######

    #######################
    # Email::fromString() #
    #######################

    /**
     * @return array
     */
    public function fromStringDataProvider(): array
    {
        return [
            'valid email' => ['user@example.com', 'user@example.com', null],
            'not valid uuid' => ['user@', null, 'Wrong email format.'],
            'empty string' => ['', null, 'Email cannot be empty.'],
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
        $actual = Email::fromString($value);

        // Then
        if (null !== $expected) {
            $this->assertInstanceOf(Email::class, $actual);
            $this->assertSame($expected, ReflectionHelper::findPropertyValue($actual, 'value'));
        }
    }

    #######
    # End #
    #######
}
