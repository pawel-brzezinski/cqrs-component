<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Broadway\EventSourcing\Fake;

use PB\Component\CQRS\Broadway\EventSourcing\BroadwayAggregateRoot;
use PB\Component\CQRS\Tests\Broadway\EventSourcing\Fake\Event\{
    FakeAggregateRootWasCreated,
    FakeAggregateRootWasDeleted,
    FakeAggregateRootWasTested
};

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class FakeAggregateRoot extends BroadwayAggregateRoot
{
    private int $id;

    private bool $wasTested = false;

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
        $self->apply(new FakeAggregateRootWasCreated($id));

        return $self;
    }

    /**
     *
     */
    public function delete(): void
    {
        $this->apply(new FakeAggregateRootWasDeleted($this->id));
    }

    /**
     * @return string
     */
    public function getAggregateRootId(): string
    {
        return 'fake-aggregate-'.$this->id;
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function wasTested(): bool
    {
        return $this->wasTested;
    }

    /**
     * @param FakeAggregateRootWasCreated $event
     */
    protected function applyFakeAggregateRootWasCreated(FakeAggregateRootWasCreated $event): void
    {
        $this->id = $event->id;
    }

    /**
     * @param FakeAggregateRootWasDeleted $event
     */
    protected function applyFakeAggregateRootWasDeleted(FakeAggregateRootWasDeleted $event): void
    {
        $this->markedAsDeleted = true;
    }

    /**
     * @param FakeAggregateRootWasTested $event
     */
    protected function applyFakeAggregateRootWasTested(FakeAggregateRootWasTested $event): void
    {
        $this->wasTested = true;
    }
}
