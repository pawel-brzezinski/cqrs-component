<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\TestCase\Persistance\Doctrine\Types\HashedPassword;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PB\Component\CQRS\Domain\String\ValueObject\Password\AbstractHashedPassword;
use PB\Component\CQRS\Persistance\Doctrine\Types\HashedPassword\AbstractHashedPasswordType;
use PB\Component\CQRS\Tests\Persistance\Doctrine\Types\HashedPassword\Fake\Fake;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class HashedPasswordTypeTestCase extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy|AbstractPlatform|null */
    private $platformMock;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->platformMock = $this->prophesize(AbstractPlatform::class);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        $this->platformMock = null;
    }

    #########################################
    # AbstractHashedPasswordType::getName() #
    #########################################

    /**
     *
     */
    public function testShouldCallGetNameMethodAndCheckIfReturnedStringIsCorrect(): void
    {
        // When
        $actual = $this->createType()->getName();

        // Then
        $this->assertSame('hashed_password', $actual);
    }

    #######
    # End #
    #######

    ########################################################
    # AbstractHashedPasswordType::requiresSQLCommentHint() #
    ########################################################

    /**
     *
     */
    public function testShouldCallRequiresSQLCommentHintMethodAndCheckIfReturnedFlagIsSetOnTrue(): void
    {
        // When
        $actual = $this->createType()->requiresSQLCommentHint($this->platformMock->reveal());

        // Then
        $this->assertTrue($actual);
    }

    #######
    # End #
    #######

    ########################################################
    # AbstractHashedPasswordType::convertToDatabaseValue() #
    ########################################################

    /**
     * @return array
     */
    public function convertToDatabaseValueDataProvider(): array
    {
        // Dataset 1
        $value1 = null;
        $expected1 = null;

        // Dataset 2
        $value2 = forward_static_call([$this->getValueObjectMotherClass(), 'random']);
        $expected2 = $value2->toString();

        // Dataset 3
        $value3 = 'not supported value';
        $expected3 = null;
        $expectedExceptionMessage3 = "Could not convert PHP value 'not supported value' to type hashed_password. Expected one of the following types: null, ".$this->getValueObjectClass();

        return [
            'value is null' => [$value1, $expected1, null],
            'value is correct hashed password value object instance' => [$value2, $expected2, null],
            'value is not expected type' => [$value3, $expected3, $expectedExceptionMessage3],
        ];
    }

    /**
     * @dataProvider convertToDatabaseValueDataProvider
     *
     * @param mixed $value
     * @param string|null $expected
     * @param string|null $expectedExceptionMessage
     */
    public function testShouldCallConvertToDatabaseValueMethodAndCheckIfReturnedValueIsExpectedString(
        $value,
        ?string $expected,
        ?string $expectedExceptionMessage
    ): void {
        // Expect
        if (null !== $expectedExceptionMessage) {
            $this->expectException(ConversionException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        // Given

        // When
        $actual = $this->createType()->convertToDatabaseValue($value, $this->platformMock->reveal());

        // Then
        if (null === $expectedExceptionMessage) {
            $this->assertSame($expected, $actual);
        }
    }

    #######
    # End #
    #######

    ###################################################
    # AbstractHashedPasswordType::convertToPHPValue() #
    ###################################################

    /**
     * @return array
     */
    public function convertToPHPValueDataProvider(): array
    {
        // Dataset 1
        $value1 = null;
        $expectedPlainPassword1 = null;

        // Dataset 2
        $value2 = forward_static_call([$this->getValueObjectMotherClass(), 'create'], 'Password2#');
        $expectedPlainPassword2 = 'Password2#';

        // Dataset 3
        $value3 = forward_static_call([$this->getValueObjectMotherClass(), 'create'], 'Password3#')->toString();
        $expectedPlainPassword3 = 'Password3#';

        // Dataset 4
        $hash4 = forward_static_call([$this->getValueObjectMotherClass(), 'create'], 'Password4#')->toString();
        $value4 = Fake::create($hash4);
        $expectedPlainPassword4 = 'Password4#';

        return [
            'value is null' => [$value1, $expectedPlainPassword1],
            'value is BcryptHashedPassword value object instance' => [$value2, $expectedPlainPassword2],
            'value is correct hash string' => [$value3, $expectedPlainPassword3],
            'value is object with correct hash string' => [$value4, $expectedPlainPassword4],
        ];
    }

    /**
     * @dataProvider convertToPHPValueDataProvider
     *
     * @param mixed $value
     * @param string|null $expectedPlainPassword
     *
     * @throws ConversionException
     */
    public function testShouldCallConvertToPHPValueMethodAndCheckIfReturnedValueIsExpectedValueObject(
        $value,
        ?string $expectedPlainPassword
    ): void {
        // Given

        // When
        $actual = $this->createType()->convertToPHPValue($value, $this->platformMock->reveal());

        // Then
        if (null === $expectedPlainPassword) {
            $this->assertNull($actual);
        } else {
            $this->assertHashedPasswordValueObject($expectedPlainPassword, $actual);
        }
    }

    #######
    # End #
    #######

    /**
     * @return string
     */
    abstract protected function getTypeClass(): string;

    /**
     * @return string
     */
    abstract protected function getValueObjectClass(): string;

    /**
     * @return string
     */
    abstract protected function getValueObjectMotherClass(): string;

    /**
     * @param string $expectedPlainPassword
     * @param AbstractHashedPassword $actual
     */
    abstract protected function assertHashedPasswordValueObject(string $expectedPlainPassword, AbstractHashedPassword $actual): void;

    /**
     * @return AbstractHashedPasswordType
     */
    protected function createType(): AbstractHashedPasswordType
    {
        $class = $this->getTypeClass();

        return new $class();
    }
}
