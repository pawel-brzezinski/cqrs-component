<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Persistance\Doctrine\Types\HashedPassword;

use PB\Component\CQRS\Domain\String\ValueObject\Password\SodiumHashedPassword;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class SodiumHashedPasswordType extends AbstractHashedPasswordType
{
    /**
     * {@inheritDoc}
     */
    protected function getValueObjectClass(): string
    {
        return SodiumHashedPassword::class;
    }
}
