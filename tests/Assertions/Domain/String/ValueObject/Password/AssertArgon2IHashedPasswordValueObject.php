<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Assertions\Domain\String\ValueObject\Password;

use PB\Component\CQRS\Domain\String\ValueObject\Password\Argon2IHashedPassword;
use PB\Component\CQRS\Tests\Assertions\PHPUnitAssertionTrait;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait AssertArgon2IHashedPasswordValueObject
{
    use PHPUnitAssertionTrait;

    /**
     * Asserts Argon2IHashedPassword value object.
     *
     * @param Argon2IHashedPassword $expected
     * @param Argon2IHashedPassword $actual
     */
    public function assertArgon2IHashedPasswordValueObject(Argon2IHashedPassword $expected, Argon2IHashedPassword $actual): void
    {
        $this->assertTrue($expected->match($actual->toString()));
    }
}
