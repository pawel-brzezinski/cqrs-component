<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Persistance\Doctrine\Types;

use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PB\Component\CQRS\Domain\Number\ValueObject\PositiveFloat;
use PB\Component\CQRS\Persistance\Doctrine\Types\PositiveFloatType;
use PB\Component\CQRS\Tests\Assertions\Domain\Number\ValueObject\AssertPositiveFloatValueObject;
use PB\Component\CQRS\Tests\MotherObject\Domain\Number\ValueObject\PositiveFloatMother;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class PositiveFloatTypeTest extends TestCase
{
    use AssertPositiveFloatValueObject;
    use ProphecyTrait;

    /** @var ObjectProphecy|AbstractPlatform|null */
    private $platformMock;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->platformMock = $this->prophesize(AbstractPlatform::class);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        $this->platformMock = null;
    }

    ################################
    # PositiveFloatType::getName() #
    ################################

    /**
     *
     */
    public function testShouldCallGetNameMethodAndCheckIfReturnedStringIsCorrectNonEmptyType(): void
    {
        // When
        $actual = $this->createType()->getName();

        // Then
        $this->assertSame('positive_float', $actual);
    }

    #######
    # End #
    #######

    ###############################################
    # PositiveFloatType::requiresSQLCommentHint() #
    ###############################################

    /**
     *
     */
    public function testShouldCallRequiresSQLCommentHintMethodAndCheckIfReturnedFlagIsSetOnTrue(): void
    {
        // When
        $actual = $this->createType()->requiresSQLCommentHint($this->platformMock->reveal());

        // Then
        $this->assertTrue($actual);
    }

    #######
    # End #
    #######

    ###############################################
    # PositiveFloatType::convertToDatabaseValue() #
    ###############################################

    /**
     * @return array
     *
     * @throws AssertionFailedException
     */
    public function convertToDatabaseValueDataProvider(): array
    {
        // Dataset 1
        $value1 = null;
        $expected1 = null;

        // Dataset 2
        $value2 = PositiveFloatMother::randomWith(['value' => 2.2]);
        $expected2 = 2.2;

        // Dataset 3
        $value3 = 'not supported value';
        $expected3 = null;
        $expectedExceptionMessage3 = "Could not convert PHP value 'not supported value' to type positive_float. Expected one of the following types: null, PB\Component\CQRS\Domain\Number\ValueObject\PositiveFloat";

        return [
            'value is null' => [$value1, $expected1, null],
            'value is PositiveFloat instance' => [$value2, $expected2, null],
            'value is not expected type' => [$value3, $expected3, $expectedExceptionMessage3],
        ];
    }

    /**
     * @dataProvider convertToDatabaseValueDataProvider
     *
     * @param mixed $value
     */
    public function testShouldCallConvertToDatabaseValueMethodAndCheckIfReturnedValueIsExpectedPositiveFloatValue(
        $value,
        ?float $expected,
        ?string $expectedExceptionMessage
    ): void {
        // Expect
        if (null !== $expectedExceptionMessage) {
            $this->expectException(ConversionException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        // Given

        // When
        $actual = $this->createType()->convertToDatabaseValue($value, $this->platformMock->reveal());

        // Then
        if (null === $expectedExceptionMessage) {
            $this->assertSame($expected, $actual);
        }
    }

    #######
    # End #
    #######

    ##########################################
    # PositiveFloatType::convertToPHPValue() #
    ##########################################

    /**
     * @return array
     *
     * @throws AssertionFailedException
     */
    public function convertToPHPValueDataProvider(): array
    {
        // Dataset 1
        $value1 = null;
        $expected1 = null;

        // Dataset 2
        $value2 = PositiveFloatMother::randomWith(['value' => 22.2]);
        $expected2 = $value2;

        // Dataset 3
        $value3 = "3.3";
        $expected3 = PositiveFloatMother::randomWith(['value' => 3.3]);

        // Dataset 4
        $value4 = 0;
        $expected4 = null;
        $expectedExceptionMessage4 = "Could not convert database value \"0\" to Doctrine Type positive_float. Expected format: float greater than 0";

        return [
            'value is null' => [$value1, $expected1, null],
            'value is PositiveFloat value object instance' => [$value2, $expected2, null],
            'value is positive float as string' => [$value3, $expected3, null],
            'value is not positive float' => [$value4, $expected4, $expectedExceptionMessage4],
        ];
    }

    /**
     * @dataProvider convertToPHPValueDataProvider
     *
     * @param mixed $value
     */
    public function testShouldCallConvertToPHPValueMethodAndCheckIfReturnedValueIsExpectedPositiveIntegerValueObject(
        $value,
        ?PositiveFloat $expected,
        ?string $expectedExceptionMessage
    ): void {
        // Expect
        if (null !== $expectedExceptionMessage) {
            $this->expectException(ConversionException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        // Given

        // When
        $actual = $this->createType()->convertToPHPValue($value, $this->platformMock->reveal());

        // Then
        if (null === $expectedExceptionMessage) {
            $this->assertPositiveFloatValueObjectOrNull($expected, $actual);
        }
    }

    #######
    # End #
    #######

    /**
     * @return PositiveFloatType
     */
    private function createType(): PositiveFloatType
    {
        return new PositiveFloatType();
    }
}
