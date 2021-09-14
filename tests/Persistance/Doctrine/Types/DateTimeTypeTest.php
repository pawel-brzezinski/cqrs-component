<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Persistance\Doctrine\Types;

use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PB\Component\CQRS\Domain\DateTime\ValueObject\DateTime as DateTimeValueObject;
use PB\Component\CQRS\Persistance\Doctrine\Types\DateTimeType;
use PB\Component\CQRS\Tests\Assertions\Domain\DateTime\ValueObject\AssertDateTimeValueObject;
use PB\Component\CQRS\Tests\Mother\Domain\DateTime\ValueObject\DateTimeMother;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use stdClass;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class DateTimeTypeTest extends TestCase
{
    use AssertDateTimeValueObject;
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

    ##########################################
    # DateTimeType::convertToDatabaseValue() #
    ##########################################

    /**
     * @return array
     */
    public function convertToDatabaseValueDataProvider(): array
    {
        // Dataset 1
        $value1 = new DateTimeImmutable('2021-03-18T21:52:00+02:00');
        $expected1 = '2021-03-18 21:52:00';

        // Dataset 2
        $value2 = DateTimeMother::randomWith(['datetime' => '2021-03-18T22:02:30+02:00']);
        $expected2 = '2021-03-18 22:02:30';

        // Dataset 3
        $value3 = null;
        $expected3 = null;

        // Dataset 4
        $value4 = new DateTime();
        $expected4 = null;
        $expectedExceptionMessage4 = "Could not convert PHP value of type DateTime to type datetime_immutable. Expected one of the following types: null, PB\Component\CQRS\Domain\DateTime\ValueObject\DateTime";

        // Dataset 5
        $value5 = new stdClass();
        $expected5 = null;
        $expectedExceptionMessage5 = "Could not convert PHP value of type stdClass to type datetime_immutable. Expected one of the following types: null, PB\Component\CQRS\Domain\DateTime\ValueObject\DateTime";

        return [
            'value is DateTimeImmutable instance' => [$value1, $expected1, null],
            'value is DateTime value object instance' => [$value2, $expected2, null],
            'value is null' => [$value3, $expected3, null],
            'value is standard \DateTime object' => [$value4, $expected4, $expectedExceptionMessage4],
            'value is has other not expected type' => [$value5, $expected5, $expectedExceptionMessage5],
        ];
    }

    /**
     * @dataProvider convertToDatabaseValueDataProvider
     *
     * @param mixed $value
     * @param string|null $expected
     * @param string|null $expectedExceptionMessage
     */
    public function testShouldCallConvertToDatabaseValueMethodAndCheckIfReturnedValueIsExpectedDateTimeString(
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

        // Mock AbstractPlatform::getDateTimeFormatString()
        if (null !== $expected) {
            $this->platformMock->getDateTimeFormatString()->shouldBeCalledTimes(1)->willReturn('Y-m-d H:i:s');
        } else {
            $this->platformMock->getDateTimeFormatString()->shouldNotBeCalled();
        }
        // End

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
    # DateTimeType::convertToPHPValue() #
    #####################################

    /**
     * @return array
     */
    public function convertToPHPValueDataProvider(): array
    {
        // Dataset 1
        $value1 = null;
        $expected1 = null;

        // Dataset 2
        $value2 = DateTimeMother::randomWith(['datetime' => '2021-03-19T09:51:11+02:00']);
        $expected2 = $value2;

        // Dataset 3
        $value3 = new DateTimeImmutable('2021-03-19T09:45:00+02:00');
        $expected3 = DateTimeMother::randomWith(['datetime' => '2021-03-19T09:45:00+02:00']);

        // Dataset 4
        $value4 = '2021-03-19 09:55:14';
        $expected4 = DateTimeMother::randomWith(['datetime' => $value4]);

        // Dataset 5
        $value5 = 'bad-format';
        $expected5 = null;
        $expectedExceptionMessage5 = "Could not convert database value \"bad-format\" to Doctrine Type datetime_immutable. Expected format: Y-m-d H:i:s";

        return [
            'value is null' => [$value1, $expected1, null],
            'value is DateTime value object instance' => [$value2, $expected2, null],
            'value is DateTimeImmutable instance' => [$value3, $expected3, null],
            'value is valid DateTime format string' => [$value4, $expected4, null],
            'value is a string with wrong DateTime format' => [$value5, $expected5, $expectedExceptionMessage5],
        ];
    }

    /**
     * @dataProvider convertToPHPValueDataProvider
     *
     * @param mixed $value
     * @param DateTimeValueObject|null $expected
     * @param string|null $expectedExceptionMessage
     */
    public function testShouldCallConvertToPHPValueMethodAndCheckIfReturnedValueIsExpectedDateTimeValueObject(
        $value,
        ?DateTimeValueObject $expected,
        ?string $expectedExceptionMessage
    ): void {
        // Expect
        if (null !== $expectedExceptionMessage) {
            $this->expectException(ConversionException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        // Given

        // Mock AbstractPlatform::getDateTimeFormatString()
        if (null !== $expectedExceptionMessage) {
            $this->platformMock->getDateTimeFormatString()->shouldBeCalledTimes(1)->willReturn('Y-m-d H:i:s');
        } else {
            $this->platformMock->getDateTimeFormatString()->shouldNotBeCalled();
        }
        // End

        // When
        $actual = $this->createType()->convertToPHPValue($value, $this->platformMock->reveal());

        // Then
        if (null === $expectedExceptionMessage) {
            // If original value is instance of DateTimeValueObject then this value should be returned directly.
            if (true === $value instanceof DateTimeValueObject) {
                $this->assertSame($value, $actual);
            } else {
                $this->assertDateTimeValueObjectOrNull($expected, $actual);
            }
        }
    }

    #######
    # End #
    #######

    /**
     * @return DateTimeType
     */
    private function createType(): DateTimeType
    {
        return new DateTimeType();
    }
}
