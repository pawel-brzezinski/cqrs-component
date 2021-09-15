<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Persistance\Doctrine\Types;

use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PB\Component\CQRS\Domain\String\ValueObject\NonEmpty;
use PB\Component\CQRS\Persistance\Doctrine\Types\NonEmptyType;
use PB\Component\CQRS\Tests\Assertions\Domain\String\ValueObject\AssertNonEmptyValueObject;
use PB\Component\CQRS\Tests\MotherObject\Domain\String\ValueObject\NonEmptyMother;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class NonEmptyTypeTest extends TestCase
{
    use AssertNonEmptyValueObject;
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

    ###########################
    # NonEmptyType::getName() #
    ###########################

    /**
     *
     */
    public function testShouldCallGetNameMethodAndCheckIfReturnedStringIsCorrectNonEmptyType(): void
    {
        // When
        $actual = $this->createType()->getName();

        // Then
        $this->assertSame('non_empty', $actual);
    }

    #######
    # End #
    #######

    ##########################################
    # NonEmptyType::requiresSQLCommentHint() #
    ##########################################

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

    ##########################################
    # NonEmptyType::convertToDatabaseValue() #
    ##########################################

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
        $value2 = NonEmptyMother::randomWith(['value' => 'Non empty 2']);
        $expected2 = 'Non empty 2';

        // Dataset 3
        $value3 = 'not supported value';
        $expected3 = null;
        $expectedExceptionMessage3 = "Could not convert PHP value 'not supported value' to type non_empty. Expected one of the following types: null, PB\Component\CQRS\Domain\String\ValueObject\NonEmpty";

        return [
            'value is null' => [$value1, $expected1, null],
            'value is NonEmpty instance' => [$value2, $expected2, null],
            'value is not expected type' => [$value3, $expected3, $expectedExceptionMessage3],
        ];
    }

    /**
     * @dataProvider convertToDatabaseValueDataProvider
     *
     * @param mixed $value
     * @param string|null $expected
     * @param string|null $expectedExceptionMessage
     */
    public function testShouldCallConvertToDatabaseValueMethodAndCheckIfReturnedValueIsExpectedNonEmptyString(
        $value,
        ?string $expected,
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

    #####################################
    # NonEmptyType::convertToPHPValue() #
    #####################################

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
        $value2 = NonEmptyMother::randomWith(['value' => 'Non empty 2']);
        $expected2 = $value2;

        // Dataset 3
        $value3 = 'Non empty 3';
        $expected3 = NonEmptyMother::randomWith(['value' => $value3]);

        // Dataset 5
        $value4 = '';
        $expected4 = null;
        $expectedExceptionMessage4 = "Could not convert database value \"\" to Doctrine Type non_empty. Expected format: non-empty string";

        return [
            'value is null' => [$value1, $expected1, null],
            'value is NonEmpty value object instance' => [$value2, $expected2, null],
            'value is non-empty string' => [$value3, $expected3, null],
            'value is empty string' => [$value4, $expected4, $expectedExceptionMessage4],
        ];
    }

    /**
     * @dataProvider convertToPHPValueDataProvider
     *
     * @param mixed $value
     * @param NonEmpty|null $expected
     * @param string|null $expectedExceptionMessage
     */
    public function testShouldCallConvertToPHPValueMethodAndCheckIfReturnedValueIsExpectedNonEmptyValueObject(
        $value,
        ?NonEmpty $expected,
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
            $this->assertNonEmptyValueObjectOrNull($expected, $actual);
        }
    }

    #######
    # End #
    #######

    /**
     * @return NonEmptyType
     */
    private function createType(): NonEmptyType
    {
        return new NonEmptyType();
    }
}
