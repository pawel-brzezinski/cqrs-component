<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Command\Fake;

use PB\Component\CQRS\Command\{CommandBusInterface, CommandBusTrait, CommandInterface};
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class FakeCommandBus
{
    use CommandBusTrait;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param CommandInterface $command
     *
     * @throws Throwable
     */
    public function callExec(CommandInterface $command): void
    {
        $this->exec($command);
    }
}
