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
     * @param Argon2IDHashedPassword $expected
     * @param Argon2IDHashedPassword $actual
     */
    public function assertArgon2IDHashedPasswordValueObject(Argon2IDHashedPassword $expected, Argon2IDHashedPassword $actual): void
    {
        $this->assertTrue($expected->match($actual->toString()));
    }
}
