<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Domain\Number\ValueObject;

use Assert\AssertionFailedException;
use PB\Component\CQRS\Domain\Number\ValueObject\Integer;
use PB\Component\FirstAid\Reflection\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use TypeError;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class IntegerTest extends TestCase
{
    #####################
    # Integer::create() #
    #####################

    /**
     * @return array
     */
    public function createDataProvider(): array
    {
        return [
            'value is 0' => [0, null],
            'value is positive integer' => [200, null],
            'value is negative integer' => [-300, null],
            'value is not integer' => ['400', new TypeError()],
        ];
    }

    /**
     * @dataProvider createDataProvider
     *
     * @param mixed $value
     * @param TypeError|null $expectedException
     *
     * @return void
     *
     * @throws AssertionFailedException
     * @throws ReflectionException
     */
    public function testShouldCallCreateStaticMethodAndCheckIfValueObjectHasBeenCreatedCorrectlyWhenValueIsInteger(
        $value,
        ?TypeError $expectedException
    ): void {
        // Expect
        if (null !== $expectedException) {
            $this->expectException(TypeError::class);
        }

        // Given

        $voUnderTest = Integer::create($value);

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

    ###################
    # Integer::dump() #
    ###################

    /**
     * @return void
     *
     * @throws AssertionFailedException
     */
    public function testShouldCallDumpMethodAndCheckIfValueObjectHasBeenDumpedToSimpleInteger(): void
    {
        // Given
        $value = 10;
        
        // When
        $actual = Integer::create($value)->dump();
        
        // Then
        $this->assertSame($value, $actual);
    }

    #######
    # End #
    #######
}
