<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Assertions\Domain\String\ValueObject\Password;

use PB\Component\CQRS\Domain\String\ValueObject\Password\SodiumHashedPassword;
use PB\Component\CQRS\Tests\Assertions\PHPUnitAssertionTrait;
use SodiumException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait AssertSodiumHashedPasswordValueObject
{
    use PHPUnitAssertionTrait;

    /**
     * Asserts SodiumHashedPassword value object.
     *
     * @param SodiumHashedPassword $expected
     * @param SodiumHashedPassword $actual
     * 
     * @throws SodiumException
     */
    public function assertSodiumHashedPasswordValueObject(SodiumHashedPassword $expected, SodiumHashedPassword $actual): void
    {
        $this->assertTrue($expected->match($actual->toString()));
    }
}
