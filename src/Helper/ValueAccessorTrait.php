<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Helper;

use Assert\{Assertion, AssertionFailedException};

/**
 * @author Wojciech Brzeziński <wojciech.brzezinski@smartint.pl>
 */
trait ValueAccessorTrait
{
    /**
     * Tries to find and return class' property value.
     *
     * This magic method serves as a generic accessor for read-only objects.
     *
     * @param string $property
     * @param array $arguments
     *
     * @return mixed
     *
     * @throws AssertionFailedException
     */
    public function __call(string $property, array $arguments)
    {
        Assertion::propertyExists($this, $property);

        return $this->$property;
    }
}
