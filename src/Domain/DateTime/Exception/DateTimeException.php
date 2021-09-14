<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Domain\DateTime\Exception;

use Exception;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class DateTimeException extends Exception
{
    public const ERROR_CODE = 500;

    /**
     * DateTimeException constructor.
     *
     * @param Exception $exception
     */
    public function __construct(Exception $exception)
    {
        parent::__construct('Datetime Malformed or not valid', self::ERROR_CODE, $exception);
    }
}
