<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Mother\Domain\String\ValueObject;

use Assert\AssertionFailedException;
use PB\Component\CQRS\Domain\String\ValueObject\NonEmpty;
use PB\Component\CQRS\Tests\Helper\FakerTrait;
use PB\Component\CQRS\Tests\Helper\Mother\SimpleValueObjectMother;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class NonEmptyMother extends SimpleValueObjectMother
{
    use FakerTrait;

    /**
     * @param array $args
     *
     * @return NonEmpty
     *
     * @throws AssertionFailedException
     */
    public static function randomWith(array $args): object
    {
        return NonEmpty::fromString(...array_values(self::randomConstructorArguments($args)));
    }

    /**
     * @return string
     */
    protected static function getClass(): string
    {
        return NonEmpty::class;
    }

    /**
     * @return array
     */
    protected static function getDefaultConstructorArguments(): array
    {
        return [
            'value' => self::getFaker()->words(3, true),
        ];
    }
}
