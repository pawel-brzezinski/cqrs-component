<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Domain\String\ValueObject\Password;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class BcryptHashedPassword extends AbstractNativeHashedPassword
{
    private const COST = 12;
    private const PASSWORD_HASH_OPTIONS = ['cost' => self::COST];

    /**
     * {@inheritDoc}
     */
    protected static function getAlgorithm(): string
    {
        return PASSWORD_BCRYPT;
    }

    /**
     * {@int}
     */
    protected static function getAlgorithmOptions(): array
    {
        return self::PASSWORD_HASH_OPTIONS;
    }
}
