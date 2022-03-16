<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\MotherObject\Domain\Number\ValueObject;

use Assert\AssertionFailedException;
use PB\Component\CQRS\Domain\Number\ValueObject\NonNegativeInteger;
use PB\Component\FirstAidTests\Faker\FakerTrait;
use PB\Component\FirstAidTests\MotherObject\SimpleValueObjectMother;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class NonNegativeIntegerMother extends SimpleValueObjectMother
{
    use FakerTrait;

    /**
     * @param array $args
     *
     * @return NonNegativeInteger
     *
     * @throws AssertionFailedException
     */
    public static function randomWith(array $args): object
    {
        return NonNegativeInteger::create(...array_values(self::randomConstructorArguments($args)));
    }

    /**
     * @param int $value
     *
     * @return NonNegativeInteger
     *
     * @throws AssertionFailedException
     */
    public static function create(int $value): NonNegativeInteger
    {
        return self::randomWith(['value' => $value]);
    }

    /**
     * @return string
     */
    protected static function getClass(): string
    {
        return NonNegativeInteger::class;
    }

    /**
     * @return array
     */
    protected static function getDefaultConstructorArguments(): array
    {
        return [
            'value' => self::getFaker()->numberBetween(0, 100),
        ];
    }
}
