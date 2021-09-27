<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Persistance\Doctrine\Types\HashedPassword\Fake;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class Fake
{
    private string $value;

    /**
     * @param string $value
     *
     * @return Fake
     */
    public static function create(string $value): Fake
    {
        $fake = new self();
        $fake->value = $value;

        return $fake;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
