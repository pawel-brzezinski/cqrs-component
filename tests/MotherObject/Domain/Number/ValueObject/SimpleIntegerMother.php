<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\MotherObject\Domain\Number\ValueObject;

use Assert\AssertionFailedException;
use PB\Component\CQRS\Domain\Number\ValueObject\SimpleInteger;
use PB\Component\FirstAidTests\Faker\FakerTrait;
use PB\Component\FirstAidTests\MotherObject\SimpleValueObjectMother;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class SimpleIntegerMother extends SimpleValueObjectMother
{
    use FakerTrait;

    /**
     * @param array $args
     *
     * @return SimpleInteger
     *
     * @throws AssertionFailedException
     */
    public static function randomWith(array $args): object
    {
        return SimpleInteger::create(...array_values(self::randomConstructorArguments($args)));
    }

    /**
     * @param int $value
     *
     * @return SimpleInteger
     *
     * @throws AssertionFailedException
     */
    public static function create(int $value): SimpleInteger
    {
        return self::randomWith(['value' => $value]);
    }

    /**
     * @return string
     */
    protected static function getClass(): string
    {
        return SimpleInteger::class;
    }

    /**
     * @return array
     */
    protected static function getDefaultConstructorArguments(): array
    {
        return [
            'value' => self::getFaker()->numberBetween(-100, 100),
        ];
    }
}
