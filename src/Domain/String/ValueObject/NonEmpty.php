<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Domain\String\ValueObject;

use Assert\{Assertion, AssertionFailedException};

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class NonEmpty
{
    protected string $notEmptyMessage = 'Value cannot be empty.';

    private string $value;

    /**
     * @param string $value
     *
     * @throws AssertionFailedException
     */
    final private function __construct(string $value)
    {
        Assertion::notEmpty($value, $this->notEmptyMessage);
        $this->assert($value);

        $this->value = $value;
    }

    /**
     * @param string $value
     *
     * @return self
     *
     * @throws AssertionFailedException
     */
    final public static function fromString(string $value): self
    {
        return new static($value);
    }

    /**
     * @param NonEmpty $target
     *
     * @return bool
     */
    final public function equals(NonEmpty $target): bool
    {
        return $this->value === $target->toString();
    }

    /**
     * @return string
     */
    final public function toString(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    final public function __toString(): string
    {
        return $this->toString();
    }

    /**
     *
     */
    protected function assert(string $value): void {}
}
