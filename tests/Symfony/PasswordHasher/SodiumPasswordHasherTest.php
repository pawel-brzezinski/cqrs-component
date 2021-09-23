<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Symfony\PasswordHasher;

use PB\Component\CQRS\Domain\String\ValueObject\Password\SodiumHashedPassword;
use PB\Component\CQRS\Symfony\PasswordHasher\SodiumPasswordHasher;
use PB\Component\FirstAid\Reflection\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use SodiumException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class SodiumPasswordHasherTest extends TestCase
{
    #################################################
    # SodiumPasswordHasher::getValueObjectClass()   #
    #                                               #
    # Lets check if hasher use correct Value Object #
    #################################################

    /**
     * @throws ReflectionException
     */
    public function testShouldCallGetValueObjectProtectedMethodAndCheckIfReturnedStringIsCorrect(): void
    {
        // When
        $actual = ReflectionHelper::callMethod($this->createHasher(), 'getValueObjectClass', []);

        // Then
        $this->assertSame(SodiumHashedPassword::class, $actual);
    }

    #######
    # End #
    #######

    ################################
    # SodiumPasswordHasher::hash() #
    ################################

    /**
     *
     */
    public function testShouldCallHashMethodAndCheckIfPlainPasswordHasBeenHashedCorrectly(): void
    {
        // Given
        $plainPassword = 'Some-Correct-Password-123-#';

        // When
        $actual = $this->createHasher()->hash($plainPassword);
        $voFromActual = SodiumHashedPassword::fromHash($actual);

        // Then
        $this->assertTrue($voFromActual->match($plainPassword));
    }

    #######
    # End #
    #######

    ##################################
    # SodiumPasswordHasher::verify() #
    ##################################

    /**
     * @return array
     *
     * @throws SodiumException
     */
    public function verifyDataProvider(): array
    {
        // Dataset 1
        $hashedPassword1 = sodium_crypto_pwhash_str('password-1', 2, 67108864);
        $plainPassword1 = 'password-1';
        $expected1 = true;

        // Dataset 2
        $hashedPassword2 = sodium_crypto_pwhash_str('password-2', 2, 67108864);
        $plainPassword2 = 'other-password-2';
        $expected2 = false;

        return [
            'plain password match to hash' => [$hashedPassword1, $plainPassword1, $expected1],
            'plain password not match to hash' => [$hashedPassword2, $plainPassword2, $expected2],
        ];
    }

    /**
     * @dataProvider verifyDataProvider()
     *
     * @param string $hashedPassword
     * @param string $plainPassword
     * @param bool $expected
     */
    public function testShouldCallVerifyMethodAndCheckIfReturnedFlagIsCorrect(
        string $hashedPassword,
        string $plainPassword,
        bool $expected
    ): void {
        // When
        $actual = $this->createHasher()->verify($hashedPassword, $plainPassword);

        // Then
        $this->assertSame($expected, $actual);
    }

    #######
    # End #
    #######

    #######################################
    # SodiumPasswordHasher::needsRehash() #
    #######################################

    /**
     * @return array
     * 
     * @throws SodiumException
     */
    public function needRehashDataProvider(): array
    {
        // Dataset 1
        $hashedPassword1 = sodium_crypto_pwhash_str('password-1', 2, 67108864);
        $expected1 = false;

        // Dataset 2
        $hashedPassword2 = sodium_crypto_pwhash_str('password-1', 2, 67108860);
        $expected2 = true;

        return [
            'hashed password not need rehash' => [$hashedPassword1, $expected1],
            'hashed password need rehash' => [$hashedPassword2, $expected2],
        ];
    }

    /**
     * @dataProvider needRehashDataProvider
     *
     * @param string $hashedPassword
     * @param bool $expected
     */
    public function testShouldCallNeedsRehashMethodAndCheckIfReturnedFlagIsCorrect(string $hashedPassword, bool $expected): void
    {
        // When
        $actual = $this->createHasher()->needsRehash($hashedPassword);

        // Then
        $this->assertSame($expected, $actual);
    }

    #######
    # End #
    #######

    /**
     * @return SodiumPasswordHasher
     */
    private function createHasher(): SodiumPasswordHasher
    {
        return new SodiumPasswordHasher();
    }
}
