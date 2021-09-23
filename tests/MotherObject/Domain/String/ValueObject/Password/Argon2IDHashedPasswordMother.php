<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\MotherObject\Domain\String\ValueObject\Password;

use PB\Component\CQRS\Domain\String\ValueObject\Password\Argon2IDHashedPassword;
use PB\Component\FirstAid\Generator\PasswordGenerator;
use PB\Component\FirstAidTests\Faker\FakerTrait;
use PB\Component\FirstAidTests\MotherObject\SimpleValueObjectMother;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class Argon2IDHashedPasswordMother extends SimpleValueObjectMother
{
    use FakerTrait;

    /**
     * @param array $args
     *
     * @return Argon2IDHashedPassword
     */
    public static function randomWith(array $args): object
    {
        return Argon2IDHashedPassword::encode(...array_values(self::randomConstructorArguments($args)));
    }

    /**
     * @param string $plainPassword
     *
     * @return Argon2IDHashedPassword
     */
    public static function create(string $plainPassword): Argon2IDHashedPassword
    {
        return self::randomWith(['plainPassword' => $plainPassword]);
    }

    /**
     * @return string
     */
    protected static function getClass(): string
    {
        return Argon2IDHashedPassword::class;
    }

    /**
     * @return array
     */
    protected static function getDefaultConstructorArguments(): array
    {
        return [
            'plainPassword' => PasswordGenerator::generate(),
        ];
    }
}
