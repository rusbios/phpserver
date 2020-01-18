<?php

namespace RusBios\Transport\Listener;

use RusBios\Transport\Event\EventInterface;

interface ListenRMQInterface
{
    /**
     * @param EventInterface $event
     * @param bool $emptyQueue
     * @return mixed
     */
    public function addEvent(EventInterface $event, $emptyQueue);

    /**
     * @return bool
     */
    public function completeExecution();
}
