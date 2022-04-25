<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\MotherObject\Domain\Number\ValueObject;

use Assert\AssertionFailedException;
use PB\Component\CQRS\Domain\Number\ValueObject\NonNegativeFloat;
use PB\Component\FirstAidTests\Faker\FakerTrait;
use PB\Component\FirstAidTests\MotherObject\SimpleValueObjectMother;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class NonNegativeFloatMother extends SimpleValueObjectMother
{
    use FakerTrait;

    /**
     * @return NonNegativeFloat
     *
     * @throws AssertionFailedException
     */
    public static function randomWith(array $args): object
    {
        return NonNegativeFloat::create(...array_values(self::randomConstructorArguments($args)));
    }

    /**
     * @throws AssertionFailedException
     */
    public static function create(float $value): NonNegativeFloat
    {
        return self::randomWith(['value' => $value]);
    }

    /**
     * @return string
     */
    protected static function getClass(): string
    {
        return NonNegativeFloat::class;
    }

    /**
     * @return array
     */
    protected static function getDefaultConstructorArguments(): array
    {
        return [
            'value' => self::getFaker()->randomFloat(2, 0, 100),
        ];
    }
}
