<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Domain\Number\ValueObject;

use Assert\{AssertionFailedException, InvalidArgumentException};
use PB\Component\CQRS\Domain\Number\ValueObject\NonNegativeFloat;
use PB\Component\FirstAid\Reflection\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class NonNegativeFloatTest extends TestCase
{
    ##############################
    # NonNegativeFloat::create() #
    ##############################

    /**
     * @return array
     */
    public function createDataProvider(): array
    {
        return [
            'value is 0.0' => [0.0, null],
            'value is positive float' => [0.1, null],
            'value is negative float' => [-0.1, new InvalidArgumentException('Value of NonNegativeFloat object must be greater or equal 0.', 213)],
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
        ?AssertionFailedException $expectedException
    ): void {
        // Expect
        if (null !== $expectedException) {
            $this->expectException(get_class($expectedException));
            $this->expectExceptionMessage($expectedException->getMessage());
        }

        // When & Then
        $actual = NonNegativeFloat::create($value);
        $this->assertInstanceOf(NonNegativeFloat::class, $actual);

        $actualValue = ReflectionHelper::findPropertyValue($actual, 'value');
        $this->assertSame($value, $actualValue);
    }

    #######
    # End #
    #######

    ############################
    # NonNegativeFloat::dump() #
    ############################

    /**
     * @return void
     * 
     * @throws AssertionFailedException
     */
    public function testShouldCallDumpMethodAndCheckIfValueObjectHasBeenDumpedToSimpleFloat(): void
    {
        // Given
        $value = 20.45;
        
        // When
        $actual = NonNegativeFloat::create($value)->dump();
        
        // Then
        $this->assertSame($value, $actual);
    }

    #######
    # End #
    #######
}
