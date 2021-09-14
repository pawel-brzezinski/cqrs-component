<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Symfony\Controller\Fake;

use PB\Component\CQRS\Command\CommandInterface;
use PB\Component\CQRS\Symfony\Controller\CommandController;
use Throwable;

/**
 *
 */
final class FakeCommandController extends CommandController
{
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
