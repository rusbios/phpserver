<?php

namespace RusBios\Transport\Event;

class TelegramEvent extends AbstractEvent
{
    const TYPE = 'telegramm';

    const ITEM_METHOD_NAME = 'method_name';
    const ITEM_MESSAGE = 'text';
    const ITEM_FROM = 'from';
    const ITEM_CHAT_ID = 'chat_id';
    const ITEM_PARSE_MODE = 'parse_mode';
    const ITEM_DISABLE_PREVIEW = 'disable_web_page_preview';
    const ITEM_NOTIFICATION = 'disable_notification';
    const ITEM_REPLY_ID = 'reply_to_message_id';
    const ITEM_REPLY_MARKUP = 'reply_markup';

    public function getBody()
    {
        $data = $this->data;
        unset($data[self::ITEM_METHOD_NAME]);
        return $data;
    }

    public function validation()
    {
        return $this->data[self::ITEM_METHOD_NAME] &&
            $this->data[self::ITEM_MESSAGE] &&
            $this->data[self::ITEM_CHAT_ID] &&
            $this->data[self::ITEM_FROM];
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return '';
    }
}
