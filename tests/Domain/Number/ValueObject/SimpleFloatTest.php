<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Domain\Number\ValueObject;

use Assert\AssertionFailedException;
use PB\Component\CQRS\Domain\Number\ValueObject\SimpleFloat;
use PB\Component\FirstAid\Reflection\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use TypeError;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class SimpleFloatTest extends TestCase
{
    #########################
    # SimpleFloat::create() #
    #########################

    /**
     * @return array
     */
    public function createDataProvider(): array
    {
        return [
            'value is 0' => [0.0, null],
            'value is positive float' => [0.1, null],
            'value is negative float' => [-0.1, null],
            'value is not float' => ['4.0', new TypeError()],
        ];
    }

    /**
     * @dataProvider createDataProvider
     *
     * @param mixed $value
     *
     * @throws AssertionFailedException
     * @throws ReflectionException
     */
    public function testShouldCallCreateStaticMethodAndCheckIfValueObjectHasBeenCreatedCorrectlyWhenValueIsFloat(
        $value,
        ?TypeError $expectedException
    ): void {
        // Expect
        if (null !== $expectedException) {
            $this->expectException(TypeError::class);
        }

        // Given

        $voUnderTest = SimpleFloat::create($value);

        // When
        $actual = ReflectionHelper::getPropertyValue($voUnderTest, 'value');

        // Then
        if (null === $expectedException) {
            $this->assertSame($value, $actual);
        }
    }

    #######
    # End #
    #######

    #########################
    # SimpleFloat::dump() #
    #########################

    /**
     * @throws AssertionFailedException
     */
    public function testShouldCallDumpMethodAndCheckIfValueObjectHasBeenDumpedToSimpleFloat(): void
    {
        // Given
        $value = 10.5;
        
        // When
        $actual = SimpleFloat::create($value)->dump();
        
        // Then
        $this->assertSame($value, $actual);
    }

    #######
    # End #
    #######
}
