<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Domain\DateTime\Exception;

use Exception;
use PB\Component\CQRS\Domain\DateTime\Exception\DateTimeException;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class DateTimeExceptionTest extends TestCase
{
    ####################################
    # DateTimeException::__construct() #
    ####################################

    /**
     * @throws DateTimeException
     */
    public function testShouldThrowDateTimeExceptionAndCheckIfThisExceptionIsCorrect(): void
    {
        // Expect
        $exception = new Exception('Standard exception', 100);

        $this->expectException(DateTimeException::class);
        $this->expectExceptionMessage('Datetime Malformed or not valid');
        $this->expectExceptionCode(500);

        // Given
        $exceptionUnderTest = new DateTimeException($exception);

        // When & Then
        $this->assertSame($exception, $exceptionUnderTest->getPrevious());

        throw $exceptionUnderTest;
    }

    #######
    # End #
    #######
}
