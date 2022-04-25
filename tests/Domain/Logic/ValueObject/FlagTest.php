<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Domain\Logic\ValueObject;

use Assert\AssertionFailedException;
use PB\Component\CQRS\Domain\Logic\ValueObject\Flag;
use PB\Component\FirstAid\Reflection\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class FlagTest extends TestCase
{
    #####################
    # Flag::fromValue() #
    #####################

    /**
     * @return array
     */
    public function fromValueDataProvider(): array
    {
        return [
            'value is real bool `true`' => [true, true, null],
            'value is real bool `false`' => [false, false, null],
            'value is int 1' => [1, true, null],
            'value is int 0' => [0, false, null],
            'value is string `1`' => ['1', true, null],
            'value is string `0`' => ['0', false, null],
            'value is string `true`' => ['true', true, null],
            'value is string `false`' => ['false', false, null],
            'value is string `on`' => ['on', true, null],
            'value is string `off`' => ['off', false, null],
            'value is string `yes`' => ['yes', true, null],
            'value is string `no`' => ['no', false, null],
            'value is string `fake`' => ['fake', null, 'Value "fake" is not valid boolean value.'],
        ];
    }

    /**
     * @dataProvider fromValueDataProvider
     *
     * @param mixed $value
     * @param bool|null $expected
     * @param string|null $expectedErrorMsg
     *
     * @return void
     *
     * @throws ReflectionException
     */
    public function testShouldCallFromValueStaticMethodAndCheckIfValueObjectHasBeenCreatedCorrectly(
        $value,
        ?bool $expected,
        ?string $expectedErrorMsg
    ): void {
        // Expect
        if (null !== $expectedErrorMsg) {
            $this->expectException(AssertionFailedException::class);
            $this->expectExceptionMessage($expectedErrorMsg);
        }

        // When
        $actual = Flag::fromValue($value);

        // Then
        $this->assertSame($expected, ReflectionHelper::getPropertyValue($actual, 'value'));
    }

    #######
    # End #
    #######

    ################
    # Flag::dump() #
    ################

    /**
     * @return void
     *
     * @throws AssertionFailedException
     */
    public function testShouldCallDumpMethodAndCheckIfReturnedBooleanIsCorrect(): void
    {
        // When
        $actual = Flag::fromValue(true);

        // Then
        $this->assertTrue($actual->dump());
    }

    #######
    # End #
    #######
}
