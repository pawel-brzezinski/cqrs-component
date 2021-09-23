<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Symfony\PasswordHasher;

use PB\Component\CQRS\Domain\String\ValueObject\Password\Argon2IDHashedPassword;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class Argon2IDPasswordHasher extends AbstractPasswordHasher
{
    /**
     * {@inheritDoc}
     */
    protected function getValueObjectClass(): string
    {
        return Argon2IDHashedPassword::class;
    }
}
