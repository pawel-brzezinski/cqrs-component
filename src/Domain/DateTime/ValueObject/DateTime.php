<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Domain\DateTime\ValueObject;

use Carbon\CarbonImmutable;
use Exception;
use PB\Component\CQRS\Domain\DateTime\Exception\DateTimeException;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class DateTime extends CarbonImmutable
{
    public const FORMAT = 'Y-m-d\TH:i:s.uP';

    /**
     * {@inheritDoc}
     */
    public static function now($tz = null): self
    {
        return parent::now($tz);
    }

    /**
     * @param string $dateTime
     *
     * @return DateTime
     *
     * @throws DateTimeException
     */
    public static function fromString(string $dateTime): self
    {
        try {
            return new self($dateTime);
        } catch (Throwable $exception) {
            throw new DateTimeException(new Exception($exception->getMessage(), $exception->getCode(), $exception));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function toString(): string
    {
        return $this->format(self::FORMAT);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        $this->settings(['toStringFormat' => self::FORMAT]);

        return parent::__toString();
    }
}
