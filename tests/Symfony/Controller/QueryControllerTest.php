<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Symfony\Controller;

use PB\Component\CQRS\Query\QueryBusInterface;
use PB\Component\CQRS\Symfony\Controller\QueryController;
use PB\Component\CQRS\Tests\Symfony\Controller\Fake\FakeQueryController;
use PB\Component\FirstAid\Reflection\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use ReflectionException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class QueryControllerTest extends TestCase
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

    ##################################
    # QueryController::__construct() #
    ##################################

    /**
     * @throws ReflectionException
     */
    public function testShouldCreateQueryControllerInstanceAndCheckIfPropertiesHasBeenSetCorrectly(): void
    {
        // Given
        $ctrlUnderTest = $this->createController();
        
        // When
        $actual = ReflectionHelper::findPropertyValue($ctrlUnderTest, 'queryBus');

        // Then
        $this->assertEquals($this->queryBusMock->reveal(), $actual);
    }
    
    #######
    # End #
    #######

    /**
     * @return QueryController
     */
    public function createController(): QueryController
    {
        return new FakeQueryController($this->queryBusMock->reveal());
    }
}
