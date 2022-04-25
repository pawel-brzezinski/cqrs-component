<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Domain\Number\ValueObject;

use Assert\{Assertion, AssertionFailedException};

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class NonNegativeFloat extends SimpleFloat
{
    private const ASSERT_LIMIT = 0;
    private const ASSERT_MESSAGE = 'Value of NonNegativeFloat object must be greater or equal 0.';

    /**
     * @throws AssertionFailedException
     */
    protected function assert(float $value): void
    {
        Assertion::greaterOrEqualThan($value, self::ASSERT_LIMIT, self::ASSERT_MESSAGE);
    }
}
