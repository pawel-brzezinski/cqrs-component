<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Domain\String\ValueObject\Password;

use Assert\AssertionFailedException;
use PB\Component\FirstAid\Assertion\Assertion;
use SodiumException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class SodiumHashedPassword extends AbstractHashedPassword
{
    private const MEMORY_LIMIT = 67108864;
    private const OPS_LIMIT = 2;

    /**
     * {@inheritDoc}
     */
    public static function rehash(string $hashedPassword): bool
    {
        return sodium_crypto_pwhash_str_needs_rehash($hashedPassword, self::OPS_LIMIT, self::MEMORY_LIMIT);
    }

    /**
     * {@inheritDoc}
     *
     * @throws SodiumException
     */
    public function match(string $plainPassword): bool
    {
        return sodium_crypto_pwhash_str_verify($this->hashedPassword, $plainPassword);
    }

    /**
     * @param string $plainPassword
     *
     * @return string
     *
     * @throws AssertionFailedException
     * @throws SodiumException
     *
     * @noinspection PhpComposerExtensionStubsInspection
     */
    protected static function hash(string $plainPassword): string
    {
        Assertion::maxLength($plainPassword, self::MAX_LENGTH);
        Assertion::password($plainPassword, self::MIN_LENGTH);

        /** @noinspection PhpComposerExtensionStubsInspection */
        return sodium_crypto_pwhash_str($plainPassword, self::OPS_LIMIT, self::MEMORY_LIMIT);
    }
}
