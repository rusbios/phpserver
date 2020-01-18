<?php

namespace RusBios\Transport\Transport;

use RusBios\Transport\Event\EventInterface;

interface TransportInterface
{
    /**
     * @param mixed|null $config
     * @return TransportInterface
     */
    public function setConfig($config = null);

    /**
     * @param EventInterface $event
     * @return bool
     */
    public function addEvent(EventInterface $event);

    /**
     * @return bool
     */
    public function send();

    /**
     * @return EventInterface[]
     */
    public function getEvents();

    /**
     * @return TransportInterface
     */
    public function clear();
}
