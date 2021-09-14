<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Symfony\EventSubscriber\EventPublisher;

use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use PB\Component\CQRS\Helper\ReflectionHelper;
use PB\Component\CQRS\Symfony\EventSubscriber\EventPublisher\AsyncBroadwayEventPublisher;
use PB\Component\CQRS\Symfony\Messenger\Bus\Event\MessengerBroadwayAsyncEventBus;
use PB\Component\CQRS\Tests\Helper\Fake\FakeClass;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use ReflectionException;
use Throwable;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class AsyncBroadwayEventPublisherTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy|MessengerBroadwayAsyncEventBus|null */
    private $eventBusMock;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->eventBusMock = $this->prophesize(MessengerBroadwayAsyncEventBus::class);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        $this->eventBusMock = null;
    }

    ######################################################
    # AsyncBroadwayEventPublisher::getSubscribedEvents() #
    ######################################################

    /**
     *
     */
    public function testShouldCallGetSubscribedEventsStaticMethodAndCheckIfReturnedArrayIsCorrect(): void
    {
        // Given
        $expected = [
            'console.terminate' => 'publish',
            'kernel.terminate' => 'publish',
        ];

        // When
        $actual = AsyncBroadwayEventPublisher::getSubscribedEvents();

        // Then
        $this->assertSame($expected, $actual);
    }

    #######
    # End #
    #######

    #########################################
    # AsyncBroadwayEventPublisher::handle() #
    #########################################

    /**
     * @throws ReflectionException
     */
    public function testShouldCallHandleMethodAndCheckIfDomainMessageHasBeenAddedToTheMessagesArray(): void
    {
        // Given
        $eventId1 = 'id-1';
        $eventPayload1 = new FakeClass();
        $eventPlayhead1 = 1;
        $eventMetadata1 = new Metadata(['meta-1']);
        $event1 = DomainMessage::recordNow($eventId1, $eventPlayhead1, $eventMetadata1, $eventPayload1);

        $eventId2 = 'id-2';
        $eventPayload2 = new FakeClass();
        $eventPlayhead2 = 2;
        $eventMetadata2 = new Metadata(['meta-2']);
        $event2 = DomainMessage::recordNow($eventId2, $eventPlayhead2, $eventMetadata2, $eventPayload2);

        $subscriberUnderTest = $this->createEventSubscriber();

        // When && Then
        $subscriberUnderTest->handle($event1);
        $this->assertCount(1, ReflectionHelper::getPropertyValue($subscriberUnderTest, 'messages'));
        $this->assertSame([$event1], ReflectionHelper::getPropertyValue($subscriberUnderTest, 'messages'));

        $subscriberUnderTest->handle($event2);
        $this->assertCount(2, ReflectionHelper::getPropertyValue($subscriberUnderTest, 'messages'));
        $this->assertSame([$event1, $event2], ReflectionHelper::getPropertyValue($subscriberUnderTest, 'messages'));
    }

    #######
    # End #
    #######

    ##########################################
    # AsyncBroadwayEventPublisher::publish() #
    ##########################################

    public function publishDataProvider(): array
    {
        $eventId1 = 'id-1';
        $eventPayload1 = new FakeClass();
        $eventPlayhead1 = 1;
        $eventMetadata1 = new Metadata(['meta-1']);
        $event1 = DomainMessage::recordNow($eventId1, $eventPlayhead1, $eventMetadata1, $eventPayload1);

        $eventId2 = 'id-2';
        $eventPayload2 = new FakeClass();
        $eventPlayhead2 = 2;
        $eventMetadata2 = new Metadata(['meta-2']);
        $event2 = DomainMessage::recordNow($eventId2, $eventPlayhead2, $eventMetadata2, $eventPayload2);

        // Dataset 1
        $messages1 = [];
        
        // Dataset 2
        $messages2 = [$event1, $event2];
        
        return [
            'empty array of messages' => [$messages1],
            'not empty array of messages' => [$messages2],
        ];
    }

    /**
     * @dataProvider publishDataProvider
     *
     * @param DomainMessage[] $messages
     *
     * @throws Throwable
     */
    public function testShouldCallPublishMessagesAndCheckIfMessagesHaveBeenSent(array $messages): void
    {
        // Expect
        
        
        // Given
        $subscriberUnderTest = $this->createEventSubscriber();
        ReflectionHelper::setPropertyValue($subscriberUnderTest, 'messages', $messages);

        // Mock MessageBusInterface::dispatch()
        if (false === empty($messages)) {
            foreach ($messages as $message) {
                $this->eventBusMock->handle($message)->shouldBeCalledOnce();
            }
        } else {
            $this->eventBusMock->handle(Argument::any())->shouldNotBeCalled();
        }
        // End
        
        // When
        $subscriberUnderTest->publish();
    }

    #######
    # End #
    #######

    /**
     * @return AsyncBroadwayEventPublisher
     */
    private function createEventSubscriber(): AsyncBroadwayEventPublisher
    {
        return new AsyncBroadwayEventPublisher($this->eventBusMock->reveal());
    }
}
