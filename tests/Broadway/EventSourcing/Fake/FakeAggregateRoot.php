<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Broadway\EventSourcing\Fake;

use Broadway\EventSourcing\EventSourcedAggregateRoot;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class FakeAggregateRoot extends EventSourcedAggregateRoot
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
     * @return string
     */
    public function getAggregateRootId(): string
    {
        return 'fake-aggregate-'.$this->id;
    }
}
