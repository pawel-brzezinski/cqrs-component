<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Domain\DateTime\ValueObject;

use Carbon\CarbonImmutable;
use Carbon\Exceptions\InvalidFormatException;
use Exception;
use PB\Component\CQRS\Domain\DateTime\Exception\DateTimeException;
use PB\Component\CQRS\Domain\DateTime\ValueObject\DateTime;
use PB\Component\CQRS\Tests\Mother\Domain\DateTime\ValueObject\DateTimeMother;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class DateTimeTest extends TestCase
{
    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        // Clear Carbon "now" mock
        CarbonImmutable::setTestNow();
    }

    ###################
    # DateTime::now() #
    ###################

    /**
     * @return array
     */
    public function nowDataProvider(): array
    {
        $nowMock = CarbonImmutable::create(2020, 3, 17, 12, 5, 15, 'Europe/Warsaw');

        // Dataset 1
        $args1 = [];
        $expected1 = $nowMock;

        // Dataset 2
        $args2 = ['Europe/London'];
        $expected2 = CarbonImmutable::create(2020, 3, 17, 11, 5, 15, 'Europe/London');

        return [
            'timezone is not defined' => [$nowMock, $args1, $expected1],
            'timezone is defined' => [$nowMock, $args2, $expected2],
        ];
    }

    /**
     * @dataProvider nowDataProvider
     *
     * @param CarbonImmutable $nowMock
     * @param array $args
     * @param CarbonImmutable $expected
     */
    public function testShouldCallNowStaticMethodAndCheckIfReturnedValueIsCorrectCarbonImmutableObject(
        CarbonImmutable $nowMock,
        array $args,
        CarbonImmutable $expected
    ): void {

        // Given
        CarbonImmutable::setTestNow($nowMock);

        // When
        $actual = DateTime::now(...$args);

        // Then
        $this->assertInstanceOf(DateTime::class, $actual);
        $this->assertSame($expected->format('c'), $actual->format('c'));
        $this->assertSame($expected->getTimezone()->getName(), $actual->getTimezone()->getName());
    }

    #######
    # End #
    #######

    ##########################
    # DateTime::fromString() #
    ##########################

    /**
     * @return array
     */
    public function fromStringDataProvider(): array
    {
        // Dataset 1
        $date1 = CarbonImmutable::create(2020, 3, 17, 12, 25,0, 'Europe/Warsaw');
        $format1 = 'c';
        $dateTime1 = $date1->format('c');
        $carbonException1 = null;

        // Dataset 2
        $format2 = null;
        $dateTime2 = 'bad-date-format';
        $carbonException2 = new InvalidFormatException(
            'DateTimeImmutable::__construct(): Failed to parse time string (bad-date-format) at position 0 (b): The timezone could not be found in the database',
            0
        );

        return [
            'date format is valid' => [$format1, $dateTime1, $carbonException1],
            'date format is not valid' => [$format2, $dateTime2, $carbonException2],
        ];
    }

    /**
     * @dataProvider fromStringDataProvider
     *
     * @param string|null $format
     * @param string $dateTime
     * @param Throwable|null $carbonException
     */
    public function testShouldCallFromStringStaticMethodAndCheckIfReturnedValueIsCorrectCarbonImmutableObject(
        ?string $format,
        string $dateTime,
        ?Throwable $carbonException
    ): void {
        // Expect
        if (null !== $carbonException) {
            $this->expectException(DateTimeException::class);
            $this->expectExceptionMessage('Datetime Malformed or not valid');
            $this->expectExceptionCode(500);
        }

        // Given

        // When && Then
        try {
            $actual = DateTime::fromString($dateTime);

            $this->assertSame($dateTime, $actual->format($format));
        } catch (DateTimeException $actualException) {
            $actualPrevException = $actualException->getPrevious();

            $this->assertInstanceOf(Exception::class, $actualPrevException);
            $this->assertSame($carbonException->getMessage(), $actualPrevException->getMessage());
            $this->assertSame((int) $carbonException->getCode(), $actualPrevException->getCode());
            $this->assertInstanceOf(get_class($carbonException), $actualPrevException->getPrevious());

            throw $actualException;
        }
    }

    #######
    # End #
    #######

    ########################
    # DateTime::toString() #
    ########################

    /**
     *
     */
    public function testShouldCallToStringMethodAndCheckIfReturnedValueIsDateTimeStringInCorrectFormat(): void
    {
        // Given
        $dateTime = DateTimeMother::randomWith([
            'datetime' => '2021-03-18T19:45:14+02:00',
        ]);
        $expected = '2021-03-18T19:45:14.000000+02:00';

        // When
        $actual = $dateTime->toString();

        // Then
        $this->assertSame($expected, $actual);
    }

    #######
    # End #
    #######

    ##########################
    # DateTime::__toString() #
    ##########################

    /**
     *
     */
    public function testShouldCallToStringMagicMethodAndCheckIfReturnedValueIsDateTimeStringInCorrectFormat(): void
    {
        // Given
        $dateTime = DateTimeMother::randomWith([
            'datetime' => '1986-09-24T06:06:07+02:00',
        ]);
        $expected = '1986-09-24T06:06:07.000000+02:00';

        // When
        $actual = (string) $dateTime;

        // Then
        $this->assertSame($expected, $actual);
    }

    #######
    # End #
    #######
}
