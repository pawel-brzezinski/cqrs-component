<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\MotherObject\Domain\String\ValueObject\Password;

use PB\Component\CQRS\Domain\String\ValueObject\Password\SodiumHashedPassword;
use PB\Component\FirstAid\Generator\PasswordGenerator;
use PB\Component\FirstAidTests\Faker\FakerTrait;
use PB\Component\FirstAidTests\MotherObject\SimpleValueObjectMother;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class SodiumHashedPasswordMother extends SimpleValueObjectMother
{
    use FakerTrait;

    /**
     * @param array $args
     *
     * @return SodiumHashedPassword
     */
    public static function randomWith(array $args): object
    {
        return SodiumHashedPassword::encode(...array_values(self::randomConstructorArguments($args)));
    }

    /**
     * @param string $plainPassword
     *
     * @return SodiumHashedPassword
     */
    public static function create(string $plainPassword): SodiumHashedPassword
    {
        return self::randomWith(['plainPassword' => $plainPassword]);
    }

    /**
     * @return string
     */
    protected static function getClass(): string
    {
        return SodiumHashedPassword::class;
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
