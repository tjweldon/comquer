<?php declare(strict_types=1);

namespace Comquer\Event;

use Comquer\DomainIntegration\Event\Event;
use Comquer\DomainIntegration\Event\EventQueue;
use Comquer\DomainIntegration\Event\EventStore;

class EventDispatcher implements \Comquer\DomainIntegration\Event\EventDispatcher
{
    /** @var EventStore */
    private $eventStore;

    /** @var EventSubscriptionProvider */
    private $eventSubscriptionProvider;

    /** @var EventQueue */
    private $eventQueue;

    public function __construct(EventStore $eventStore, EventSubscriptionProvider $eventSubscriptionProvider, EventQueue $eventQueue)
    {
        $this->eventStore = $eventStore;
        $this->eventSubscriptionProvider = $eventSubscriptionProvider;
        $this->eventQueue = $eventQueue;
    }

    public function dispatch(Event $event) : void
    {
        $this->eventStore->persist($event);
        $subscriptions = $this->eventSubscriptionProvider->getForEvent($event);

        /** @var EventSubscription $subscription */
        foreach ($subscriptions as $subscription) {
            $this->eventQueue->push(new EventQueueItem($event, $subscription->getListenerName()));
        }
    }
}
