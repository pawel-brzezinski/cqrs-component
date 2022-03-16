<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Domain\Number\ValueObject;

use Assert\AssertionFailedException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class SimpleInteger
{
    private int $value;

    /**
     * @param int $value
     *
     * @return static
     *
     * @throws AssertionFailedException
     */
    final public static function create(int $value): self
    {
        return new static($value);
    }

    /**
     * @return int
     */
    final public function dump(): int
    {
        return $this->value;
    }

    /**
     * Asserts the value.
     * For simple integer we don't have to add any assertions. Argument type is enough.
     *
     * @param int $value
     *
     * @return void
     *
     * @throws AssertionFailedException
     *
     * @noinspection PhpDocRedundantThrowsInspection
     */
    protected function assert(int $value): void {}

    /**
     * @param int $value
     *
     * @throws AssertionFailedException
     */
    final private function __construct(int $value)
    {
        $this->assert($value);
        $this->value = $value;
    }
}
