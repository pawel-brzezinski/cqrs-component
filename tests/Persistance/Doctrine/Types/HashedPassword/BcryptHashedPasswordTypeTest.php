<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Persistance\Doctrine\Types\HashedPassword;

use PB\Component\CQRS\Domain\String\ValueObject\Password\AbstractHashedPassword;
use PB\Component\CQRS\Domain\String\ValueObject\Password\BcryptHashedPassword;
use PB\Component\CQRS\Persistance\Doctrine\Types\HashedPassword\BcryptHashedPasswordType;
use PB\Component\CQRS\Tests\Assertions\Domain\String\ValueObject\Password\AssertBcryptHashedPasswordValueObject;
use PB\Component\CQRS\Tests\MotherObject\Domain\String\ValueObject\Password\BcryptHashedPasswordMother;
use PB\Component\CQRS\Tests\TestCase\Persistance\Doctrine\Types\HashedPassword\HashedPasswordTypeTestCase;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class BcryptHashedPasswordTypeTest extends HashedPasswordTypeTestCase
{
    use AssertBcryptHashedPasswordValueObject;

    /**
     * {@inheritDoc}
     */
    protected function getTypeClass(): string
    {
        return BcryptHashedPasswordType::class;
    }

    /**
     * {@inheritDoc}
     */
    protected function getValueObjectClass(): string
    {
        return BcryptHashedPassword::class;
    }

    /**
     * {@inheritDoc}
     */
    protected function getValueObjectMotherClass(): string
    {
        return BcryptHashedPasswordMother::class;
    }

    /**
     * @param string $expectedPlainPassword
     * @param BcryptHashedPassword $actual
     */
    protected function assertHashedPasswordValueObject(string $expectedPlainPassword, AbstractHashedPassword $actual): void
    {
        $this->assertBcryptHashedPasswordValueObject($expectedPlainPassword, $actual);
    }
}
