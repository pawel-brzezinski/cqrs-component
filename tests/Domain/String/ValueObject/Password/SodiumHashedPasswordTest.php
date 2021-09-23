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
        $plainPassword1 = file_get_contents(__DIR__.'/assets/valid_4096_chars.txt');
        $expectAssertionError1 = false;

        // Dataset 2
        $plainPassword2 = file_get_contents(__DIR__.'/assets/not_valid_4096_chars.txt');
        $expectAssertionError2 = true;

        // Dataset 3
        $plainPassword3 = file_get_contents(__DIR__.'/assets/valid_4097_chars.txt');
        $expectAssertionError3 = true;

        return [
            'plain password is valid' => [$plainPassword1, $expectAssertionError1],
            'plain password is not valid - missing special chars' => [$plainPassword2, $expectAssertionError2],
            'plain password is not valid - string is too long' => [$plainPassword3, $expectAssertionError3],
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

    ##################################
    # SodiumHashedPassword::rehash() #
    ##################################

    /**
     * @return array
     *
     * @throws SodiumException
     */
    public function rehashDataProvider(): array
    {
        // Dataset 1
        $hashedPassword1 = sodium_crypto_pwhash_str('Password-1', 2, 67108864);
        $expected1 = false;

        // Dataset 2
        $hashedPassword2 = sodium_crypto_pwhash_str('Password-2', 2, 67108863);
        $expected2 = true;

        return [
            'password not need rehash' => [$hashedPassword1, $expected1],
            'password need rehash - options not match' => [$hashedPassword2, $expected2],
        ];
    }

    /**
     * @dataProvider rehashDataProvider
     *
     * @param string $hashedPassword
     * @param bool $expected
     */
    public function testShouldCallRehashStaticMethodAndCheckIfReturnedFlagIsCorrect(string $hashedPassword, bool $expected): void
    {
        // When
        $actual = SodiumHashedPassword::rehash($hashedPassword);

        // Then
        $this->assertSame($expected, $actual);
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
    public function testShouldCallMatchMethodAndCheckIfReturnedFlagIsCorrect(
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
