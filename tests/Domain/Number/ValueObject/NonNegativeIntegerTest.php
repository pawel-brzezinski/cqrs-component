<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Domain\Number\ValueObject;

use Assert\{AssertionFailedException, InvalidArgumentException};
use PB\Component\CQRS\Domain\Number\ValueObject\NonNegativeInteger;
use PB\Component\FirstAid\Reflection\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class NonNegativeIntegerTest extends TestCase
{
    ################################
    # NonNegativeInteger::create() #
    ################################

    /**
     * @return array
     */
    public function createDataProvider(): array
    {
        return [
            'value is 0' => [0, null],
            'value is positive integer' => [1, null],
            'value is negative integer' => [-1, new InvalidArgumentException('Value of NonNegativeInteger object must be greater or equal 0.', 213)],
        ];
    }

    /**
     * @dataProvider createDataProvider
     *
     * @param mixed $value
     * @param AssertionFailedException|null $expectedException
     *
     * @return void
     *
     * @throws AssertionFailedException
     * @throws ReflectionException
     */
    public function testShouldCallCreateStaticMethodAndCheckIfValueObjectHasBeenCreatedCorrectlyWhenValueIsInteger(
        $value,
        ?AssertionFailedException $expectedException
    ): void {
        // Expect
        if (null !== $expectedException) {
            $this->expectException(get_class($expectedException));
            $this->expectExceptionMessage($expectedException->getMessage());
        }

        // When & Then
        $actual = NonNegativeInteger::create($value);
        $this->assertInstanceOf(NonNegativeInteger::class, $actual);

        $actualValue = ReflectionHelper::findPropertyValue($actual, 'value');
        $this->assertSame($value, $actualValue);
    }

    #######
    # End #
    #######

    ##############################
    # NonNegativeInteger::dump() #
    ##############################

    /**
     * @return void
     * 
     * @throws AssertionFailedException
     */
    public function testShouldCallDumpMethodAndCheckIfValueObjectHasBeenDumpedToSimpleInteger(): void
    {
        // Given
        $value = 20;
        
        // When
        $actual = NonNegativeInteger::create($value)->dump();
        
        // Then
        $this->assertSame($value, $actual);
    }

    #######
    # End #
    #######
}
