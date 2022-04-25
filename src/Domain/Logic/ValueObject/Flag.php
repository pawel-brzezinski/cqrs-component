<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Domain\Logic\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class Flag
{
    private const ASSERTION_MSG = 'Value "%s" is not valid boolean value.';

    private bool $value;

    /**
     * @param bool $value
     */
    private function __construct(bool $value)
    {
        $this->value = $value;
    }

    /**
     * @param mixed $value
     *
     * @return static
     *
     * @throws AssertionFailedException
     */
    public static function fromValue($value): self
    {
        $boolean = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        Assertion::notNull($boolean, sprintf(self::ASSERTION_MSG, $value));

        return new self($boolean);
    }

    /**
     * @return bool
     */
    public function dump(): bool
    {
        return $this->value;
    }
}
