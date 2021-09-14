<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Domain\String\ValueObject;

use Assert\{Assertion, AssertionFailedException};

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class Email extends NonEmpty
{
    protected string $notEmptyMessage = 'Email cannot be empty.';

    private string $emailFormatMessage = 'Wrong email format.';

    /**
     * {@inheritDoc}
     *
     * @throws AssertionFailedException
     */
    protected function assert(string $value): void
    {
        Assertion::email($value, $this->emailFormatMessage);
    }
}