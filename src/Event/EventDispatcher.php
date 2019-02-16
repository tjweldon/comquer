<?php declare(strict_types=1);

namespace CQRS\Event;

use CQRS\Queue\Queue;

class EventDispatcher
{
    private $registeredEvents;

    private $queue;

    public function __construct(RegisteredEvents $registeredEvents, Queue $queue)
    {
        $this->registeredEvents = $registeredEvents;
        $this->queue = $queue;
    }

    public function dispatch($event)
    {
        $this->registeredEvents->mustContain($event);

        return $this->queue->push($event);
    }
}