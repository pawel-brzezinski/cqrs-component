<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Domain\Number\ValueObject;

use Assert\AssertionFailedException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class SimpleFloat
{
    private float $value;

    /**
     * @return static
     *
     * @throws AssertionFailedException
     */
    final public static function create(float $value): self
    {
        return new static($value);
    }

    final public function dump(): float
    {
        return $this->value;
    }

    /**
     * Asserts the value.
     * For simple float we don't have to add any assertions. Argument type is enough.
     *
     * @throws AssertionFailedException
     *
     * @noinspection PhpDocRedundantThrowsInspection
     */
    protected function assert(float $value): void {}

    /**
     * @throws AssertionFailedException
     */
    final private function __construct(float $value)
    {
        $this->assert($value);
        $this->value = $value;
    }
}
