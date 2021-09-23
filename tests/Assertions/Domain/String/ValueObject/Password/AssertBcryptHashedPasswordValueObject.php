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
     * @param BcryptHashedPassword $expected
     * @param BcryptHashedPassword $actual
     */
    public function assertBcryptHashedPasswordValueObject(BcryptHashedPassword $expected, BcryptHashedPassword $actual): void
    {
        $this->assertTrue($expected->match($actual->toString()));
    }
}
