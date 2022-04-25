<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Persistance\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\FloatType;
use PB\Component\CQRS\Domain\Number\ValueObject\PositiveFloat;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class PositiveFloatType extends FloatType
{
    private const TYPE = 'positive_float';

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
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?float
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof PositiveFloat) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', PositiveFloat::class]);
        }

        return $value->dump();
    }

    /**
     * {@inheritDoc}
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?PositiveFloat
    {
        if (null === $value || true === $value instanceof PositiveFloat) {
            return $value;
        }

        try {
            return PositiveFloat::create((float) $value);
        } catch (Throwable $exception) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), 'float greater than 0');
        }
    }
}
