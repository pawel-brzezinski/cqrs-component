<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Persistance\Doctrine\Types\HashedPassword;

use PB\Component\CQRS\Domain\String\ValueObject\Password\Argon2IHashedPassword;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class Argon2IHashedPasswordType extends AbstractHashedPasswordType
{
    /**
     * {@inheritDoc}
     */
    protected function getValueObjectClass(): string
    {
        return Argon2IHashedPassword::class;
    }
}
