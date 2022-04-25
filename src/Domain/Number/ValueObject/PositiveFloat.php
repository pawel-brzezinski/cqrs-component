<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Domain\Number\ValueObject;

use Assert\{Assertion, AssertionFailedException};

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class PositiveFloat extends SimpleFloat
{
    private const ASSERT_LIMIT = 0;
    private const ASSERT_MESSAGE = 'Value of PositiveFloat object must be greater than 0.';

    /**
     * @throws AssertionFailedException
     */
    protected function assert(float $value): void
    {
        Assertion::greaterThan($value, self::ASSERT_LIMIT, self::ASSERT_MESSAGE);
    }
}
