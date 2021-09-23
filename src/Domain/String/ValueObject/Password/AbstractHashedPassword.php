<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Domain\String\ValueObject\Password;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class AbstractHashedPassword
{
    protected string $hashedPassword;

    /**
     * @param string $hashedPassword
     */
    final private function __construct(string $hashedPassword)
    {
        $this->hashedPassword = $hashedPassword;
    }

    /**
     * @param string $plainPassword
     *
     * @return static
     */
    public static function encode(string $plainPassword): self
    {
        return new static(static::hash($plainPassword));
    }

    /**
     * @param string $hashedPassword
     *
     * @return static
     */
    public static function fromHash(string $hashedPassword): self
    {
        return new static($hashedPassword);
    }

    public function toString(): string
    {
        return $this->hashedPassword;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->hashedPassword;
    }

    /**
     * @param string $plainPassword
     *
     * @return bool
     */
    abstract public function match(string $plainPassword): bool;

    /**
     * @param string $plainPassword
     *
     * @return string
     */
    abstract protected static function hash(string $plainPassword): string;
}
