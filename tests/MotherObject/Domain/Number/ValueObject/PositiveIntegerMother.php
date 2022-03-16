<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\MotherObject\Domain\Number\ValueObject;

use Assert\AssertionFailedException;
use PB\Component\CQRS\Domain\Number\ValueObject\PositiveInteger;
use PB\Component\FirstAidTests\Faker\FakerTrait;
use PB\Component\FirstAidTests\MotherObject\SimpleValueObjectMother;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class PositiveIntegerMother extends SimpleValueObjectMother
{
    use FakerTrait;

    /**
     * @param array $args
     *
     * @return PositiveInteger
     *
     * @throws AssertionFailedException
     */
    public static function randomWith(array $args): object
    {
        return PositiveInteger::create(...array_values(self::randomConstructorArguments($args)));
    }

    /**
     * @param int $value
     *
     * @return PositiveInteger
     *
     * @throws AssertionFailedException
     */
    public static function create(int $value): PositiveInteger
    {
        return self::randomWith(['value' => $value]);
    }

    /**
     * @return string
     */
    protected static function getClass(): string
    {
        return PositiveInteger::class;
    }

    /**
     * @return array
     */
    protected static function getDefaultConstructorArguments(): array
    {
        return [
            'value' => self::getFaker()->numberBetween(1, 100),
        ];
    }
}
