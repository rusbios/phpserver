<?php

namespace RusBios\Transport\Transport;

use Exception;
use RusBios\Transport\Event\EventInterface;

abstract class AbstractTransport implements TransportInterface
{
    /** @var EventInterface[] */
    protected $events;

    /**
     * @param EventInterface $event
     * @return bool
     */
    public function addEvent(EventInterface $event)
    {
        if ($event->validation() && !array_search($event->getIncrement(), array_keys($this->events))) {
            $this->events[$event->getIncrement()] = $event;
            return true;
        }
        return false;
    }

    public function send()
    {
        $success = true;
        foreach ($this->events as &$event) {
            try {
                $event->setResult($this->make($event));
            } catch (Exception $e) {
                $event->setResult($e->getMessage());
                $success = false;
            }
        }
        return $success;
    }

    /**
     * @param EventInterface $event
     * @return mixed
     * @throws Exception
     */
    protected abstract function make(EventInterface $event);

    /**
     * @return EventInterface[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @return TransportInterface
     */
    public function clear()
    {
        unset($this->events);
        return $this;
    }
}
