<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Helper;

use Assert\AssertionFailedException;
use PB\Component\CQRS\Tests\Helper\Fake\FakeClass;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class ValueAccessorTraitTest extends TestCase
{
    ################################
    # ValueAccessorTrait::__call() #
    ################################

    /**
     * @return array
     */
    public function callDataProvider(): array
    {
        return [
            'property in object exist' => [new FakeClass(), 'sit', 'sit'],
            'property in object not exist ' => [new FakeClass(), 'bad-property', null],
        ];
    }

    /**
     * @dataProvider callDataProvider
     *
     * @param object $object
     * @param string $property
     * @param mixed $expected
     *
     * @throws AssertionFailedException
     */
    public function testShouldUseCallMagicMethodAndCheckIfObjectPropertyHasBeenReturnedCorrectlyOrExceptionHasBeenThrown(
        object $object,
        string $property,
        $expected
    ): void {
        // Expect
        if (null === $expected) {
            $this->expectException(AssertionFailedException::class);
        }

        // Given


        // When
        $actual = $object->$property();

        // Then
        if (null !== $expected) {
            $this->assertSame($expected, $actual);
        }
    }

    #######
    # End #
    #######
}
