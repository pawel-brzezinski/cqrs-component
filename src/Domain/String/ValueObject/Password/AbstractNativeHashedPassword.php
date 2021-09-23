<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Domain\String\ValueObject\Password;

use PB\Component\FirstAid\Assertion\Assertion;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class AbstractNativeHashedPassword extends AbstractHashedPassword
{
    /**
     * @param string $hashedPassword
     *
     * @return bool
     */
    public static function rehash(string $hashedPassword): bool
    {
        return password_needs_rehash($hashedPassword, static::getAlgorithm(), static::getAlgorithmOptions());
    }

    /**
     * @param string $plainPassword
     *
     * @return bool
     */
    public function match(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hashedPassword);
    }

    /**
     * @return string
     */
    abstract protected static function getAlgorithm(): string;

    /**
     * @return array
     */
    abstract protected static function getAlgorithmOptions(): array;

    /**
     * {@inheritDoc}
     */
    protected static function hash(string $plainPassword): string
    {
        Assertion::maxLength($plainPassword, self::MAX_LENGTH);
        Assertion::password($plainPassword, self::MIN_LENGTH);

        return password_hash($plainPassword, static::getAlgorithm(), static::getAlgorithmOptions());
    }
}
