<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Persistance\Doctrine\Types;

use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use PB\Component\CQRS\Domain\DateTime\Exception\DateTimeException;
use PB\Component\CQRS\Domain\DateTime\ValueObject\DateTime;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class DateTimeType extends DateTimeImmutableType
{
    /**
     * {@inheritDoc}
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof DateTimeImmutable) {
            return $value->format($platform->getDateTimeFormatString());
        }

        throw ConversionException::conversionFailedInvalidType(
            $value,
            $this->getName(),
            ['null', DateTime::class]
        );
    }

    /**
     * {@inheritDoc}
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?DateTime
    {
        if (null === $value || $value instanceof DateTime) {
            return $value;
        }

        if ($value instanceof DateTimeImmutable) {
            $value = $value->format(DateTime::FORMAT);
        }

        try {
            $dateTime = DateTime::fromString($value);
        } catch (DateTimeException $exception) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }

        return $dateTime;
    }
}
