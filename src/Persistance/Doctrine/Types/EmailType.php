<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Persistance\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;
use PB\Component\CQRS\Domain\String\ValueObject\Email;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class EmailType extends StringType
{
    private const TYPE = 'email';

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

        if (!$value instanceof Email) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', Email::class]);
        }

        return $value->toString();
    }

    /**
     * {@inheritDoc}
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Email
    {
        if (null === $value || true === $value instanceof Email) {
            return $value;
        }

        try {
            /** @var Email $email */
            $email = Email::fromString($value);
        } catch (Throwable $exception) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), 'email string');
        }

        return $email;
    }
}
