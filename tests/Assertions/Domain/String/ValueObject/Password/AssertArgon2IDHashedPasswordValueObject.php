<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Assertions\Domain\String\ValueObject\Password;

use PB\Component\CQRS\Domain\String\ValueObject\Password\Argon2IDHashedPassword;
use PB\Component\CQRS\Tests\Assertions\PHPUnitAssertionTrait;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait AssertArgon2IDHashedPasswordValueObject
{
    use PHPUnitAssertionTrait;

    /**
     * Asserts Argon2IDHashedPassword value object.
     *
     * @param string $expectedPlainPassword
     * @param Argon2IDHashedPassword $actual
     */
    public function assertArgon2IDHashedPasswordValueObject(string $expectedPlainPassword, Argon2IDHashedPassword $actual): void
    {
        $this->assertTrue($actual->match($expectedPlainPassword));
        $this->assertFalse(Argon2IDHashedPassword::rehash($actual->toString()));
    }
}
