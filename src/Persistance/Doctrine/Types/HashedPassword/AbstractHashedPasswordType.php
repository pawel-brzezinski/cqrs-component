<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Persistance\Doctrine\Types\HashedPassword;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;
use PB\Component\CQRS\Domain\String\ValueObject\Password\AbstractHashedPassword;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class AbstractHashedPasswordType extends StringType
{
    private const TYPE = 'hashed_password';

    /**
     * @return string
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
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        $voClass = static::getValueObjectClass();

        if (false === is_object($value) || $voClass !== get_class($value)) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', $voClass]);
        }

        return $value->toString();
    }

    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?AbstractHashedPassword
    {
        $voClass = static::getValueObjectClass();

        if (null === $value || (true === is_object($value) && $voClass === get_class($value))) {
            return $value;
        }

        return forward_static_call([$voClass, 'fromHash'], $value);
    }

    /**
     * @return string
     */
    abstract protected function getValueObjectClass(): string;
}
