<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Assertions\Domain\String\ValueObject\Password;

use PB\Component\CQRS\Domain\String\ValueObject\Password\BcryptHashedPassword;
use PB\Component\CQRS\Tests\Assertions\PHPUnitAssertionTrait;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait AssertBcryptHashedPasswordValueObject
{
    use PHPUnitAssertionTrait;

    /**
     * Asserts BcryptHashedPassword value object.
     *
     * @param string $expectedPlainPassword
     * @param BcryptHashedPassword $actual
     */
    public function assertBcryptHashedPasswordValueObject(string $expectedPlainPassword, BcryptHashedPassword $actual): void
    {
        $this->assertTrue($actual->match($expectedPlainPassword));
        $this->assertFalse(BcryptHashedPassword::rehash($actual->toString()));
    }
}
