<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Domain\String\ValueObject\Password;

use Assert\AssertionFailedException;
use PB\Component\CQRS\Domain\String\ValueObject\Password\SodiumHashedPassword;
use PB\Component\CQRS\Tests\Assertions\AssertObjectConstructor;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use SodiumException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class SodiumHashedPasswordTest extends TestCase
{
    use AssertObjectConstructor;

    #######################################
    # SodiumHashedPassword::__construct() #
    #######################################

    /**
     * @throws ReflectionException
     */
    public function testShouldCheckIfValueObjectConstructorIsNotPublic(): void
    {
        // Then
        $this->assertConstructorIsNotPublic(SodiumHashedPassword::class);
    }

    #######
    # End #
    #######

    ##################################
    # SodiumHashedPassword::encode() #
    ##################################

    /**
     * @return array
     */
    public function encodeDataProvider(): array
    {
        // Dataset 1
        $plainPassword1 = 'AsDe12%6';
        $expectAssertionError1 = false;

        // Dataset 2
        $plainPassword2 = 'NoTVaLiD';
        $expectAssertionError2 = true;

        return [
            'plain password is valid' => [$plainPassword1, $expectAssertionError1],
            'plain password is not valid' => [$plainPassword2, $expectAssertionError2],
        ];
    }

    /**
     * @dataProvider encodeDataProvider
     *
     * @param string $plainPassword
     * @param bool $expectAssertionError
     *
     * @throws SodiumException
     */
    public function testShouldCallHashStaticMethodAndUseMagicToStringMethodToCheckIfPlainPasswordHasBeenHashedAndSetCorrectly(
        string $plainPassword,
        bool $expectAssertionError
    ): void {
        // Expect
        if (true === $expectAssertionError) {
            $this->expectException(AssertionFailedException::class);
        }

        // Given

        // When
        $actual = SodiumHashedPassword::encode($plainPassword);

        // Then
        if (false === $expectAssertionError) {
            $this->assertTrue(sodium_crypto_pwhash_str_verify((string)$actual, $plainPassword));
        }
    }

    #######
    # End #
    #######

    ####################################
    # SodiumHashedPassword::fromHash() #
    ####################################

    /**
     *
     */
    public function testShouldCallFromHashStaticMethodAndUseToStringMagicMethodToCheckIfValueObjectHasBeenCreatedCorrectlyFromHashedPassword(): void
    {
        // Given
        $plainPass = 'Some-correct-password-123-%';
        $hashedPass = sodium_crypto_pwhash_str($plainPass, 2, 67108864);

        // When
        $actual = SodiumHashedPassword::fromHash($hashedPass);
        
        // Then
        $this->assertTrue(password_verify($plainPass, (string)$actual));
    }
    
    #######
    # End #
    #######

    #################################
    # SodiumHashedPassword::match() #
    #################################

    /**
     * @return array
     */
    public function matchDataProvider(): array
    {
        // Dataset 1
        $orgPlainPassword1 = 'AsDe12%6';
        $plainPassword1 = $orgPlainPassword1;
        $expected1 = true;

        // Dataset 2
        $orgPlainPassword2 = 'qWeR&8$0';
        $plainPassword2 = 'qwerty12';
        $expected2 = false;

        return [
            'passwords match' => [$orgPlainPassword1, $plainPassword1, $expected1],
            'passwords not match' => [$orgPlainPassword2, $plainPassword2, $expected2],
        ];
    }

    /**
     * @dataProvider matchDataProvider
     *
     * @param string $orgPlainPassword
     * @param string $plainPassword
     * @param bool $expected
     *
     * @throws SodiumException
     */
    public function testShouldCallMatchMethodAndUseMagicToStringMethodToCheckIfPlainPasswordHasBeenHashedAndSetCorrectly(
        string $orgPlainPassword,
        string $plainPassword,
        bool $expected
    ): void {
        // Given
        $valueObjectUnderTest = SodiumHashedPassword::encode($orgPlainPassword);

        // When
        $actual = $valueObjectUnderTest->match($plainPassword);

        // Then
        $this->assertSame($expected, $actual);
    }

    #######
    # End #
    #######

    ####################################
    # SodiumHashedPassword::toString() #
    ####################################

    /**
     *
     */
    public function testShouldCallToStringMethodAndUseCheckIfReturnedStringIsCorrect(): void
    {
        // Given
        $plainPass = 'Some-correct-password-456-%';

        // When
        $actual = SodiumHashedPassword::encode($plainPass)->toString();

        // Then
        $this->assertTrue(sodium_crypto_pwhash_str_verify($actual, $plainPass));
    }

    #######
    # End #
    #######
}
