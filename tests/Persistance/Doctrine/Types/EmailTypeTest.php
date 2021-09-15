<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Persistance\Doctrine\Types;

use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PB\Component\CQRS\Domain\String\ValueObject\Email;
use PB\Component\CQRS\Persistance\Doctrine\Types\EmailType;
use PB\Component\CQRS\Tests\Assertions\Domain\String\ValueObject\AssertEmailValueObject;
use PB\Component\CQRS\Tests\MotherObject\Domain\String\ValueObject\EmailMother;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@db-team.pl>
 */
final class EmailTypeTest extends TestCase
{
    use AssertEmailValueObject;
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

    ########################
    # EmailType::getName() #
    ########################

    /**
     *
     */
    public function testShouldCallGetNameMethodAndCheckIfReturnedStringIsCorrectEmailType(): void
    {
        // When
        $actual = $this->createType()->getName();

        // Then
        $this->assertSame('email', $actual);
    }

    #######
    # End #
    #######

    #######################################
    # EmailType::requiresSQLCommentHint() #
    #######################################

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

    #######################################
    # EmailType::convertToDatabaseValue() #
    #######################################

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
        $value2 = EmailMother::randomWith(['value' => 'user-1@example.com']);
        $expected2 = 'user-1@example.com';

        // Dataset 3
        $value3 = 'not supported value';
        $expected3 = null;
        $expectedExceptionMessage3 = "Could not convert PHP value 'not supported value' to type email. Expected one of the following types: null, PB\Component\CQRS\Domain\String\ValueObject\Email";

        return [
            'value is null' => [$value1, $expected1, null],
            'value is Email instance' => [$value2, $expected2, null],
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
    public function testShouldCallConvertToDatabaseValueMethodAndCheckIfReturnedValueIsExpectedEmailString(
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

    ##################################
    # EmailType::convertToPHPValue() #
    ##################################

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
        $value2 = EmailMother::randomWith(['value' => 'user-2@example.com']);
        $expected2 = $value2;

        // Dataset 3
        $value3 = 'user-3@example.com';
        $expected3 = EmailMother::randomWith(['value' => $value3]);

        // Dataset 5
        $value4 = '';
        $expected4 = null;
        $expectedExceptionMessage4 = "Could not convert database value \"\" to Doctrine Type email. Expected format: email string";

        return [
            'value is null' => [$value1, $expected1, null],
            'value is Email value object instance' => [$value2, $expected2, null],
            'value is email string' => [$value3, $expected3, null],
            'value is empty string' => [$value4, $expected4, $expectedExceptionMessage4],
        ];
    }

    /**
     * @dataProvider convertToPHPValueDataProvider
     *
     * @param mixed $value
     * @param Email|null $expected
     * @param string|null $expectedExceptionMessage
     */
    public function testShouldCallConvertToPHPValueMethodAndCheckIfReturnedValueIsExpectedEmailValueObject(
        $value,
        ?Email $expected,
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
            $this->assertEmailValueObjectOrNull($expected, $actual);
        }
    }

    #######
    # End #
    #######

    /**
     * @return EmailType
     */
    private function createType(): EmailType
    {
        return new EmailType();
    }
}
