<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Broadway\EventSourcing\Fake;

use PB\Component\CQRS\Broadway\EventSourcing\BroadwayAggregateRoot;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class FakeAggregateRoot extends BroadwayAggregateRoot
{
    private int $id;

    /**
     *
     */
    private function __construct() {}

    /**
     * @param int $id
     *
     * @return FakeAggregateRoot
     */
    public static function create(int $id): self
    {
        $self = new self();
        // Should be applied by domain event
        $self->id = $id;

        return $self;
    }

    /**
     *
     */
    public function delete(): void
    {
        // Should be applied by domain event
        $this->markedAsDeleted = true;
    }

    /**
     * @return string
     */
    public function getAggregateRootId(): string
    {
        return 'fake-aggregate-'.$this->id;
    }
}
