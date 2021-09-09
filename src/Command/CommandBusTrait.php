<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Command;

use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait CommandBusTrait
{
    private CommandBusInterface $commandBus;

    /**
     * @param CommandInterface $command
     *
     * @throws Throwable
     */
    protected function exec(CommandInterface $command): void
    {
        $this->commandBus->handle($command);
    }
}
