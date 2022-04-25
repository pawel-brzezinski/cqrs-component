<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Domain\Number\ValueObject;

use Assert\{AssertionFailedException, InvalidArgumentException};
use PB\Component\CQRS\Domain\Number\ValueObject\PositiveFloat;
use PB\Component\FirstAid\Reflection\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class PositiveFloatTest extends TestCase
{
    ###########################
    # PositiveFloat::create() #
    ###########################

    /**
     * @return array
     */
    public function createDataProvider(): array
    {
        return [
            'value is 0.1' => [0.1, null],
            'value is 0.0' => [0.0, new InvalidArgumentException('Value of PositiveFloat object must be greater than 0.', 212)],
            'value is -0.1' => [-0.1, new InvalidArgumentException('Value of PositiveFloat object must be greater than 0.', 212)],
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
        $actual = PositiveFloat::create($value);
        $this->assertInstanceOf(PositiveFloat::class, $actual);

        $actualValue = ReflectionHelper::findPropertyValue($actual, 'value');
        $this->assertSame($value, $actualValue);
    }

    #######
    # End #
    #######

    #########################
    # PositiveFloat::dump() #
    #########################

    /**
     * @return void
     * 
     * @throws AssertionFailedException
     */
    public function testShouldCallDumpMethodAndCheckIfValueObjectHasBeenDumpedToSimpleFloat(): void
    {
        // Given
        $value = 14.78;
        
        // When
        $actual = PositiveFloat::create($value)->dump();
        
        // Then
        $this->assertSame($value, $actual);
    }

    #######
    # End #
    #######
}
