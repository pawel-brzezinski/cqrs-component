<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Persistance\Doctrine\Types\HashedPassword;

use PB\Component\CQRS\Domain\String\ValueObject\Password\AbstractHashedPassword;
use PB\Component\CQRS\Domain\String\ValueObject\Password\Argon2IHashedPassword;
use PB\Component\CQRS\Persistance\Doctrine\Types\HashedPassword\Argon2IHashedPasswordType;
use PB\Component\CQRS\Tests\Assertions\Domain\String\ValueObject\Password\AssertArgon2IHashedPasswordValueObject;
use PB\Component\CQRS\Tests\MotherObject\Domain\String\ValueObject\Password\Argon2IHashedPasswordMother;
use PB\Component\CQRS\Tests\TestCase\Persistance\Doctrine\Types\HashedPassword\HashedPasswordTypeTestCase;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class Argon2IHashedPasswordTypeTest extends HashedPasswordTypeTestCase
{
    use AssertArgon2IHashedPasswordValueObject;

    /**
     * {@inheritDoc}
     */
    protected function getTypeClass(): string
    {
        return Argon2IHashedPasswordType::class;
    }

    /**
     * {@inheritDoc}
     */
    protected function getValueObjectClass(): string
    {
        return Argon2IHashedPassword::class;
    }

    /**
     * {@inheritDoc}
     */
    protected function getValueObjectMotherClass(): string
    {
        return Argon2IHashedPasswordMother::class;
    }

    /**
     * @param string $expectedPlainPassword
     * @param Argon2IHashedPassword $actual
     */
    protected function assertHashedPasswordValueObject(string $expectedPlainPassword, AbstractHashedPassword $actual): void
    {
        $this->assertArgon2IHashedPasswordValueObject($expectedPlainPassword, $actual);
    }
}
