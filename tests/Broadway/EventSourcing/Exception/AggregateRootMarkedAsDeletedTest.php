<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Broadway\EventSourcing\Exception;

use PB\Component\CQRS\Broadway\EventSourcing\Exception\AggregateRootMarkedAsDeleted;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class AggregateRootMarkedAsDeletedTest extends TestCase
{
    ###############################################
    # AggregateRootMarkedAsDeleted::__construct() #
    ###############################################

    /**
     * @throws AggregateRootMarkedAsDeleted
     */
    public function testShouldThrowAggregateRootMarkedAsDeletedExceptionAndCheckIfMessageIsCorrect(): void
    {
        // Expect
        $this->expectException(AggregateRootMarkedAsDeleted::class);
        $this->expectExceptionMessage('Aggregate root `fake-aggregate-1` cannot be modified because is marked as deleted.');

        // When
        throw new AggregateRootMarkedAsDeleted('fake-aggregate-1');
    }

    #######
    # End #
    #######
}
