<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Domain\Number\ValueObject;

use Assert\{AssertionFailedException, InvalidArgumentException};
use PB\Component\CQRS\Domain\Number\ValueObject\PositiveInteger;
use PB\Component\FirstAid\Reflection\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class PositiveIntegerTest extends TestCase
{
    #############################
    # PositiveInteger::create() #
    #############################

    /**
     * @return array
     */
    public function createDataProvider(): array
    {
        return [
            'value is 1' => [1, null],
            'value is 0' => [0, new InvalidArgumentException('Value of PositiveInteger object must be greater than 0.', 212)],
            'value is -1' => [-1, new InvalidArgumentException('Value of PositiveInteger object must be greater than 0.', 212)],
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
        $actual = PositiveInteger::create($value);
        $this->assertInstanceOf(PositiveInteger::class, $actual);

        $actualValue = ReflectionHelper::findPropertyValue($actual, 'value');
        $this->assertSame($value, $actualValue);
    }

    #######
    # End #
    #######

    ###########################
    # PositiveInteger::dump() #
    ###########################

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
        $actual = PositiveInteger::create($value)->dump();
        
        // Then
        $this->assertSame($value, $actual);
    }

    #######
    # End #
    #######
}
