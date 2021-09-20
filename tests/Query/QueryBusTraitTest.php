<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Query;

use PB\Component\CQRS\Query\QueryBusInterface;
use PB\Component\CQRS\Tests\Query\Fake\{FakeQuery, FakeQueryBus};
use PB\Component\FirstAid\Reflection\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\{MethodProphecy, ObjectProphecy};
use ReflectionException;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class QueryBusTraitTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy|QueryBusInterface|null */
    private $queryBusMock;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->queryBusMock = $this->prophesize(QueryBusInterface::class);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        $this->queryBusMock = null;
    }

    ########################
    # QueryBusTrait::ask() #
    ########################

    /**
     * @throws Throwable
     */
    public function testShouldCallAskTraitMethodAndCheckIfQueryBusHandleMethodHasBeenCalledAndResultHasBeenReturned(): void
    {
        // Given
        $query = new FakeQuery(100);
        $expected = 'query-bus-result';

        // Mock QueryBusInterface::handle()
        /** @var MethodProphecy $methodProp */
        $methodProp = $this->queryBusMock->handle(Argument::is($query));
        $methodProp->shouldBeCalledTimes(1)->willReturn($expected);
        // End
        
        // When
        $actual = $this->createFakeQueryBus()->callAsk($query);

        // Then
        $this->assertSame($expected, $actual);
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldCheckIfAskMethodIsProtected(): void
    {
        // When
        $actual = ReflectionHelper::method(FakeQueryBus::class, 'ask');

        // Then
        $this->assertTrue($actual->isProtected());
    }

    #######
    # End #
    #######

    /**
     * @return FakeQueryBus
     */
    private function createFakeQueryBus(): FakeQueryBus
    {
        return new FakeQueryBus($this->queryBusMock->reveal());
    }
}
