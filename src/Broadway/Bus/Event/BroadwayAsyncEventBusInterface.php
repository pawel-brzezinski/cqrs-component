<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Broadway\Bus\Event;

use Broadway\Domain\DomainMessage;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
interface BroadwayAsyncEventBusInterface
{
    /**
     * @param DomainMessage $command
     *
     * @throws Throwable
     */
    public function handle(DomainMessage $command): void;
}
