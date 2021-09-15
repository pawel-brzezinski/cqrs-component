<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Broadway\Repository;

use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\ReflectionAggregateFactory;
use Broadway\EventSourcing\EventStreamDecorator;
use Broadway\EventStore\EventStore;
use PB\Component\CQRS\Tests\Broadway\EventSourcing\Fake\FakeAggregateRoot;
use PB\Component\CQRS\Tests\Broadway\Repository\Fake\FakeBroadwayStore;
use PB\Component\FirstAid\Reflection\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use ReflectionException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class BroadwayStoreTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy|EventStore|null */
    private $eventStoreMock;

    /** @var ObjectProphecy|EventBus|null */
    private $eventBusMock;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->eventStoreMock = $this->prophesize(EventStore::class);
        $this->eventBusMock = $this->prophesize(EventBus::class);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        $this->eventStoreMock = null;
        $this->eventBusMock = null;
    }

    ####################################
    # BroadwayStoreTest::__construct() #
    ####################################

    /**
     * @return array
     */
    public function constructorDataProvider(): array
    {
        $eventStreamDecorator1 = $this->prophesize(EventStreamDecorator::class);
        $eventStreamDecorator2 = $this->prophesize(EventStreamDecorator::class);

        // Dataset 1
        $eventStreamDecorators1 = [];

        // Dataset 2
        $eventStreamDecorators2 = [$eventStreamDecorator1, $eventStreamDecorator2];

        return [
            'empty decorators array' => [$eventStreamDecorators1],
            'not empty decorators array' => [$eventStreamDecorators2],
        ];
    }

    /**
     * @dataProvider constructorDataProvider
     *
     * @param array $eventStreamDecorators
     *
     * @throws ReflectionException
     */
    public function testShouldCreateStoreInstanceAndCheckIfPropertiesHasBeenSetCorrectly(array $eventStreamDecorators): void
    {
        // When
        $actual = $this->createStore($eventStreamDecorators);

        // Then
        $this->assertSame($this->eventStoreMock->reveal(), ReflectionHelper::findPropertyValue($actual, 'eventStore'));
        $this->assertSame($this->eventBusMock->reveal(), ReflectionHelper::findPropertyValue($actual, 'eventBus'));
        $this->assertSame(FakeAggregateRoot::class, ReflectionHelper::findPropertyValue($actual, 'aggregateClass'));
        $this->assertInstanceOf(ReflectionAggregateFactory::class, ReflectionHelper::findPropertyValue($actual, 'aggregateFactory'));
        $this->assertSame($eventStreamDecorators, ReflectionHelper::findPropertyValue($actual, 'eventStreamDecorators'));
    }

    #######
    # End #
    #######

    /**
     * @param array $eventStreamDecorators
     *
     * @return FakeBroadwayStore
     */
    private function createStore(array $eventStreamDecorators = []): FakeBroadwayStore
    {
        $args = [
            $this->eventStoreMock->reveal(),
            $this->eventBusMock->reveal(),
            FakeAggregateRoot::class,
        ];

        if (false === empty($eventStreamDecorators)) {
            $args[] = $eventStreamDecorators;
        }

        return new FakeBroadwayStore(...$args);
    }
}
