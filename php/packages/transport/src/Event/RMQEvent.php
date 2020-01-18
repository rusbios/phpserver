<?php

namespace RusBios\Transport\Event;

class RMQEvent extends AbstractEvent
{
    const TYPE = 'rmq';
    const INCREMENT = 'guid';

    const ITEM_TYPE = 'entity_type';
    const ITEM_ACTION = 'action';
    const ITEM_SOURCE = 'source';
    const ITEM_TARGET = 'target';
    const ITEM_PRIORITY = 'priority';

    public function getBody()
    {
        $data = $this->data;
        unset($data[self::ITEM_TARGET], $data[self::ITEM_ACTION], $data[self::ITEM_TYPE]);
        return $data;
    }

    public function validation()
    {
        return parent::validation() &&
            $this->data[self::ITEM_ACTION] &&
            $this->data[self::ITEM_TYPE] &&
            $this->data[self::ITEM_SOURCE] &&
            is_array($this->data[self::ITEM_TARGET]);
    }
}
