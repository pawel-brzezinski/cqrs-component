<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Persistance\Doctrine\Types;

use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PB\Component\CQRS\Domain\Number\ValueObject\PositiveInteger;
use PB\Component\CQRS\Persistance\Doctrine\Types\PositiveIntegerType;
use PB\Component\CQRS\Tests\Assertions\Domain\Number\ValueObject\AssertPositiveIntegerValueObject;
use PB\Component\CQRS\Tests\MotherObject\Domain\Number\ValueObject\PositiveIntegerMother;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class PositiveIntegerTypeTest extends TestCase
{
    use AssertPositiveIntegerValueObject;
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

    ##################################
    # PositiveIntegerType::getName() #
    ##################################

    /**
     *
     */
    public function testShouldCallGetNameMethodAndCheckIfReturnedStringIsCorrectNonEmptyType(): void
    {
        // When
        $actual = $this->createType()->getName();

        // Then
        $this->assertSame('positive_integer', $actual);
    }

    #######
    # End #
    #######

    #################################################
    # PositiveIntegerType::requiresSQLCommentHint() #
    #################################################

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

    #################################################
    # PositiveIntegerType::convertToDatabaseValue() #
    #################################################

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
        $value2 = PositiveIntegerMother::randomWith(['value' => 2]);
        $expected2 = 2;

        // Dataset 3
        $value3 = 'not supported value';
        $expected3 = null;
        $expectedExceptionMessage3 = "Could not convert PHP value 'not supported value' to type positive_integer. Expected one of the following types: null, PB\Component\CQRS\Domain\Number\ValueObject\PositiveInteger";

        return [
            'value is null' => [$value1, $expected1, null],
            'value is PositiveInteger instance' => [$value2, $expected2, null],
            'value is not expected type' => [$value3, $expected3, $expectedExceptionMessage3],
        ];
    }

    /**
     * @dataProvider convertToDatabaseValueDataProvider
     *
     * @param mixed $value
     * @param int|null $expected
     * @param string|null $expectedExceptionMessage
     */
    public function testShouldCallConvertToDatabaseValueMethodAndCheckIfReturnedValueIsExpectedPositiveIntegerValue(
        $value,
        ?int $expected,
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

    ############################################
    # PositiveIntegerType::convertToPHPValue() #
    ############################################

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
        $value2 = PositiveIntegerMother::randomWith(['value' => 2]);
        $expected2 = $value2;

        // Dataset 3
        $value3 = "3";
        $expected3 = PositiveIntegerMother::randomWith(['value' => 3]);

        // Dataset 4
        $value4 = 0;
        $expected4 = null;
        $expectedExceptionMessage4 = "Could not convert database value \"0\" to Doctrine Type positive_integer. Expected format: integer greater than 0";

        return [
            'value is null' => [$value1, $expected1, null],
            'value is PositiveInteger value object instance' => [$value2, $expected2, null],
            'value is positive integer as string' => [$value3, $expected3, null],
            'value is not positive integer' => [$value4, $expected4, $expectedExceptionMessage4],
        ];
    }

    /**
     * @dataProvider convertToPHPValueDataProvider
     *
     * @param mixed $value
     * @param PositiveInteger|null $expected
     * @param string|null $expectedExceptionMessage
     */
    public function testShouldCallConvertToPHPValueMethodAndCheckIfReturnedValueIsExpectedPositiveIntegerValueObject(
        $value,
        ?PositiveInteger $expected,
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
            $this->assertPositiveIntegerValueObjectOrNull($expected, $actual);
        }
    }

    #######
    # End #
    #######

    /**
     * @return PositiveIntegerType
     */
    private function createType(): PositiveIntegerType
    {
        return new PositiveIntegerType();
    }
}
