<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Command;

use PB\Component\CQRS\Command\CommandBusInterface;
use PB\Component\CQRS\Tests\Command\Fake\{FakeCommand, FakeCommandBus};
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class CommandBusTraitTest extends TestCase
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

    ###########################
    # CommandBusTrait::exec() #
    ###########################

    public function testShouldExecTraitMethodAndCheckIfCommandBusHandleMethodHasBeenCalled(): void
    {
        // Given
        $command = new FakeCommand(100);

        // Mock CommandBusInterface::handle()
        $this->commandBusMock->handle(Argument::is($command))->shouldBeCalledTimes(1);
        // End
        
        // When
        $this->createFakeCommandBus()->callExec($command);
    }

    #######
    # End #
    #######

    /**
     * @return FakeCommandBus
     */
    private function createFakeCommandBus(): FakeCommandBus
    {
        return new FakeCommandBus($this->commandBusMock->reveal());
    }
}
