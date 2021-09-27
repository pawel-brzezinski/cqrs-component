<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Persistance\Doctrine\Types\HashedPassword;

use PB\Component\CQRS\Domain\String\ValueObject\Password\AbstractHashedPassword;
use PB\Component\CQRS\Domain\String\ValueObject\Password\SodiumHashedPassword;
use PB\Component\CQRS\Persistance\Doctrine\Types\HashedPassword\SodiumHashedPasswordType;
use PB\Component\CQRS\Tests\Assertions\Domain\String\ValueObject\Password\AssertSodiumHashedPasswordValueObject;
use PB\Component\CQRS\Tests\MotherObject\Domain\String\ValueObject\Password\SodiumHashedPasswordMother;
use PB\Component\CQRS\Tests\TestCase\Persistance\Doctrine\Types\HashedPassword\HashedPasswordTypeTestCase;
use SodiumException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class SodiumHashedPasswordTypeTest extends HashedPasswordTypeTestCase
{
    use AssertSodiumHashedPasswordValueObject;

    /**
     * {@inheritDoc}
     */
    protected function getTypeClass(): string
    {
        return SodiumHashedPasswordType::class;
    }

    /**
     * {@inheritDoc}
     */
    protected function getValueObjectClass(): string
    {
        return SodiumHashedPassword::class;
    }

    /**
     * {@inheritDoc}
     */
    protected function getValueObjectMotherClass(): string
    {
        return SodiumHashedPasswordMother::class;
    }

    /**
     * @param string $expectedPlainPassword
     * @param SodiumHashedPassword $actual
     *
     * @throws SodiumException
     */
    protected function assertHashedPasswordValueObject(string $expectedPlainPassword, AbstractHashedPassword $actual): void
    {
        $this->assertSodiumHashedPasswordValueObject($expectedPlainPassword, $actual);
    }
}
