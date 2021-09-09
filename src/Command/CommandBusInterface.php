<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Command;

use Throwable;

/**
 *
 */
interface CommandBusInterface
{
    /**
     * @param CommandInterface $command
     *
     * @throws Throwable
     */
    public function handle(CommandInterface $command): void;
}
