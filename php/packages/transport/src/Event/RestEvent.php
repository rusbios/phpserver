<?php

namespace RusBios\Transport\Event;

class RestEvent extends AbstractEvent
{
    const TYPE = 'rest';
    const ITEM_TIMEOUT = 'timeout';
    const ITEM_AUTH = 'auth';
    const ITEM_METHOD = 'method';
    const ITEM_URL = 'url';
    const ITEM_CONTENT_TYPE = 'content_type';
    const ITEM_BODY = 'body';

    const METHOD_GET = 'get';
    const METHOD_PUT = 'put';
    const METHOD_POST = 'post';
    const METHOD_DELETED = 'deleted';
    const METHOD_PATCH = 'patch';

    const DEFAULT_CONTENT_TYPE = 'application/json';

    public function validation()
    {
        return $this->data[self::ITEM_METHOD] &&
            (
                $this->data[self::ITEM_METHOD] == self::METHOD_GET ||
                $this->data[self::ITEM_METHOD] == self::METHOD_PUT ||
                $this->data[self::ITEM_METHOD] == self::METHOD_PATCH ||
                $this->data[self::ITEM_METHOD] == self::METHOD_POST ||
                $this->data[self::ITEM_METHOD] == self::METHOD_DELETED
            ) &&
            $this->data[self::ITEM_TIMEOUT] &&
            $this->data[self::ITEM_URL] &&
            $this->data[self::ITEM_BODY];
    }
}
