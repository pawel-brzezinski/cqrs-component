<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Broadway\EventSourcing;

use Exception;
use PB\Component\CQRS\Broadway\EventSourcing\Exception\AggregateRootMarkedAsDeleted;
use PB\Component\CQRS\Tests\Broadway\EventSourcing\Fake\Event\FakeAggregateRootWasTested;
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

    ##################################
    # BroadwayAggregateRoot::apply() #
    ##################################

    /**
     * @return array
     */
    public function applyDataProvider(): array
    {
        // Dataset 1
        $aggregateRoot1 = FakeAggregateRoot::create(1);

        // Dataset 2
        $aggregateRoot2 = FakeAggregateRoot::create(2);
        $aggregateRoot2->delete();

        return [
            'aggregate root is not marked as deleted' => [$aggregateRoot1],
            'aggregate root is marked as deleted' => [$aggregateRoot2],
        ];
    }

    /**
     * @dataProvider applyDataProvider
     *
     * @param FakeAggregateRoot $aggregateRoot
     */
    public function testShouldCallApplyMethodAndCheckIfAggregateRootMarkedAsDeletedExceptionThrownWhenAggregateRootIsMarkedAsDeleted(
        FakeAggregateRoot $aggregateRoot
    ): void {
        // Expect
        if (true === $aggregateRoot->isMarkedAsDeleted()) {
            $this->expectException(AggregateRootMarkedAsDeleted::class);
            $this->expectExceptionMessage('Aggregate root `'.$aggregateRoot->getAggregateRootId().'` cannot be modified because is marked as deleted.');
        }

        // Given
        $event = new FakeAggregateRootWasTested($aggregateRoot->id());

        // When & Then
        try {
            $aggregateRoot->apply($event);

            $this->assertTrue($aggregateRoot->wasTested());
        } catch (Exception $exception) {
            $this->assertFalse($aggregateRoot->wasTested());

            throw $exception;
        }
    }
    
    #######
    # End #
    #######
}
