<?php

namespace RusBios\Transport\Event;

class SmsRuEvent extends AbstractEvent
{
    const TYPE = 'smsru';
    const INCREMENT = 'phone';

    const ITEM_MESSAGE = 'message';
    const ITEM_PHONE = self::INCREMENT;

    public function getBody()
    {
        return $this->getItem(self::ITEM_MESSAGE);
    }

    public function validation()
    {
        return parent::validation() && $this->data[self::ITEM_MESSAGE];
    }
}
