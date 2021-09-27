<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Persistance\Doctrine\Types\HashedPassword;

use PB\Component\CQRS\Domain\String\ValueObject\Password\AbstractHashedPassword;
use PB\Component\CQRS\Domain\String\ValueObject\Password\Argon2IDHashedPassword;
use PB\Component\CQRS\Persistance\Doctrine\Types\HashedPassword\Argon2IDHashedPasswordType;
use PB\Component\CQRS\Tests\Assertions\Domain\String\ValueObject\Password\AssertArgon2IDHashedPasswordValueObject;
use PB\Component\CQRS\Tests\MotherObject\Domain\String\ValueObject\Password\Argon2IDHashedPasswordMother;
use PB\Component\CQRS\Tests\TestCase\Persistance\Doctrine\Types\HashedPassword\HashedPasswordTypeTestCase;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class Argon2IDHashedPasswordTypeTest extends HashedPasswordTypeTestCase
{
    use AssertArgon2IDHashedPasswordValueObject;

    /**
     * {@inheritDoc}
     */
    protected function getTypeClass(): string
    {
        return Argon2IDHashedPasswordType::class;
    }

    /**
     * {@inheritDoc}
     */
    protected function getValueObjectClass(): string
    {
        return Argon2IDHashedPassword::class;
    }

    /**
     * {@inheritDoc}
     */
    protected function getValueObjectMotherClass(): string
    {
        return Argon2IDHashedPasswordMother::class;
    }

    /**
     * @param string $expectedPlainPassword
     * @param Argon2IDHashedPassword $actual
     */
    protected function assertHashedPasswordValueObject(string $expectedPlainPassword, AbstractHashedPassword $actual): void
    {
        $this->assertArgon2IDHashedPasswordValueObject($expectedPlainPassword, $actual);
    }
}
