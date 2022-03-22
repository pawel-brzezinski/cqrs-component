<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Persistance\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\IntegerType;
use PB\Component\CQRS\Domain\Number\ValueObject\PositiveInteger;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class PositiveIntegerType extends IntegerType
{
    private const TYPE = 'positive_integer';

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return self::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof PositiveInteger) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', PositiveInteger::class]);
        }

        return $value->dump();
    }

    /**
     * {@inheritDoc}
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?PositiveInteger
    {
        if (null === $value || true === $value instanceof PositiveInteger) {
            return $value;
        }

        try {
            return PositiveInteger::create((int) $value);
        } catch (Throwable $exception) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), 'integer greater than 0');
        }
    }
}
