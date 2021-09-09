<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Framework\Symfony\Controller;

use PB\Component\CQRS\Command\{CommandBusInterface, CommandBusTrait};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class CommandController extends AbstractController
{
    use CommandBusTrait;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }
}
