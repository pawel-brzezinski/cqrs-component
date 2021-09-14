<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Symfony\Controller;

use PB\Component\CQRS\Command\CommandBusInterface;
use PB\Component\CQRS\Symfony\Controller\CommandController;
use PB\Component\CQRS\Tests\Symfony\Controller\Fake\FakeCommandController;
use PB\Component\FirstAid\Reflection\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use ReflectionException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class CommandControllerTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy|CommandBusInterface|null */
    private $commandBusMock;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->commandBusMock = $this->prophesize(CommandBusInterface::class);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        $this->commandBusMock = null;
    }

    ####################################
    # CommandController::__construct() #
    ####################################

    /**
     * @throws ReflectionException
     */
    public function testShouldCreateCommandControllerInstanceAndCheckIfPropertiesHasBeenSetCorrectly(): void
    {
        // Given
        $ctrlUnderTest = $this->createController();
        
        // When
        $actual = ReflectionHelper::findPropertyValue($ctrlUnderTest, 'commandBus');

        // Then
        $this->assertEquals($this->commandBusMock->reveal(), $actual);
    }
    
    #######
    # End #
    #######

    /**
     * @return CommandController
     */
    public function createController(): CommandController
    {
        return new FakeCommandController($this->commandBusMock->reveal());
    }
}
