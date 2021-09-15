<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Broadway\EventSourcing;

use PB\Component\CQRS\Tests\Broadway\EventSourcing\Fake\FakeAggregateRoot;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class BroadwayAggregateRootTest extends TestCase
{
    ##############################################
    # BroadwayAggregateRoot::isMarkedAsDeleted() #
    ##############################################

    /**
     *
     */
    public function testShouldCallIsMarkedAsDeletedMethodAndCheckIfReturnedFlagIsCorrect(): void
    {
        // Given
        $aggregateRootUnderTest = FakeAggregateRoot::create(1);

        // When & Then
        $this->assertFalse($aggregateRootUnderTest->isMarkedAsDeleted());

        $aggregateRootUnderTest->delete();
        $this->assertTrue($aggregateRootUnderTest->isMarkedAsDeleted());
    }

    #######
    # End #
    #######
}
