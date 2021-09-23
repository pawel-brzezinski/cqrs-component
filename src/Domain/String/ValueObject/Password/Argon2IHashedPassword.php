<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Domain\String\ValueObject\Password;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class Argon2IHashedPassword extends AbstractNativeHashedPassword
{
    private const MEMORY_COST = 65536;
    private const THREADS = 1;
    private const TIME_COST = 4;
    private const PASSWORD_HASH_OPTIONS = [
        'memory_cost' => self::MEMORY_COST,
        'threads' => self::THREADS,
        'time_cost' => self::TIME_COST,
    ];

    /**
     * {@inheritDoc}
     */
    protected static function getAlgorithm(): string
    {
        return PASSWORD_ARGON2I;
    }

    /**
     * {@int}
     */
    protected static function getAlgorithmOptions(): array
    {
        return self::PASSWORD_HASH_OPTIONS;
    }
}
