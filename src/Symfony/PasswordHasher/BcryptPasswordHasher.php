<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Symfony\PasswordHasher;

use PB\Component\CQRS\Domain\String\ValueObject\Password\BcryptHashedPassword;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class BcryptPasswordHasher extends AbstractPasswordHasher
{
    /**
     * {@inheritDoc}
     */
    protected function getValueObjectClass(): string
    {
        return BcryptHashedPassword::class;
    }
}
