<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\MotherObject\Domain\String\ValueObject\Password;

use PB\Component\CQRS\Domain\String\ValueObject\Password\Argon2IHashedPassword;
use PB\Component\FirstAid\Generator\PasswordGenerator;
use PB\Component\FirstAidTests\Faker\FakerTrait;
use PB\Component\FirstAidTests\MotherObject\SimpleValueObjectMother;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class Argon2IHashedPasswordMother extends SimpleValueObjectMother
{
    use FakerTrait;

    /**
     * @param array $args
     *
     * @return Argon2IHashedPassword
     */
    public static function randomWith(array $args): object
    {
        return Argon2IHashedPassword::encode(...array_values(self::randomConstructorArguments($args)));
    }

    /**
     * @param string $plainPassword
     *
     * @return Argon2IHashedPassword
     */
    public static function create(string $plainPassword): Argon2IHashedPassword
    {
        return self::randomWith(['plainPassword' => $plainPassword]);
    }

    /**
     * @return string
     */
    protected static function getClass(): string
    {
        return Argon2IHashedPassword::class;
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
