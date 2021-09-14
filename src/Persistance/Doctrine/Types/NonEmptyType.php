<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Persistance\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;
use PB\Component\CQRS\Domain\String\ValueObject\NonEmpty;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class NonEmptyType extends StringType
{
    private const TYPE = 'non_empty';

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
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof NonEmpty) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', NonEmpty::class]);
        }

        return $value->toString();
    }

    /**
     * {@inheritDoc}
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?NonEmpty
    {
        if (null === $value || true === $value instanceof NonEmpty) {
            return $value;
        }

        try {
            return NonEmpty::fromString($value);
        } catch (Throwable $exception) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), 'non-empty string');
        }
    }
}
