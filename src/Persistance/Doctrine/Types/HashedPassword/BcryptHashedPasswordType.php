<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Persistance\Doctrine\Types\HashedPassword;

use PB\Component\CQRS\Domain\String\ValueObject\Password\BcryptHashedPassword;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class BcryptHashedPasswordType extends AbstractHashedPasswordType
{
    /**
     * {@inheritDoc}
     */
    protected function getValueObjectClass(): string
    {
        return BcryptHashedPassword::class;
    }
}
