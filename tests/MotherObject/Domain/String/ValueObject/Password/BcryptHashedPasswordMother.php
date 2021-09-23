<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\MotherObject\Domain\String\ValueObject\Password;

use PB\Component\CQRS\Domain\String\ValueObject\Password\BcryptHashedPassword;
use PB\Component\FirstAid\Generator\PasswordGenerator;
use PB\Component\FirstAidTests\Faker\FakerTrait;
use PB\Component\FirstAidTests\MotherObject\SimpleValueObjectMother;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class BcryptHashedPasswordMother extends SimpleValueObjectMother
{
    use FakerTrait;

    /**
     * @param array $args
     *
     * @return BcryptHashedPassword
     */
    public static function randomWith(array $args): object
    {
        return BcryptHashedPassword::encode(...array_values(self::randomConstructorArguments($args)));
    }

    /**
     * @param string $plainPassword
     *
     * @return BcryptHashedPassword
     */
    public static function create(string $plainPassword): BcryptHashedPassword
    {
        return self::randomWith(['plainPassword' => $plainPassword]);
    }

    /**
     * @return string
     */
    protected static function getClass(): string
    {
        return BcryptHashedPassword::class;
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
