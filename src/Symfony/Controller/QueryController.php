<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Symfony\Controller;

use PB\Component\CQRS\Query\{QueryBusInterface, QueryBusTrait};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class QueryController extends AbstractController
{
    use QueryBusTrait;

    /**
     * @param QueryBusInterface $queryBus
     */
    public function __construct(QueryBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }
}
