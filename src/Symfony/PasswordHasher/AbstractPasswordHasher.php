<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Symfony\PasswordHasher;

use Symfony\Component\PasswordHasher\PasswordHasherInterface;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class AbstractPasswordHasher implements PasswordHasherInterface
{
    /**
     * {@inheritDoc}
     */
    public function hash(string $plainPassword): string
    {
        return forward_static_call([$this->getValueObjectClass(), 'encode'], $plainPassword)->toString();
    }

    /**
     * {@inheritDoc}
     */
    public function verify(string $hashedPassword, string $plainPassword): bool
    {
        return forward_static_call([$this->getValueObjectClass(), 'fromHash'], $hashedPassword)->match($plainPassword);
    }

    /**
     * {@inheritDoc}
     */
    public function needsRehash(string $hashedPassword): bool
    {
        return forward_static_call([$this->getValueObjectClass(), 'rehash'], $hashedPassword);
    }

    /**
     * @return string
     */
    abstract protected function getValueObjectClass(): string;
}
