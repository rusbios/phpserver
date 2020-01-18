<?php

namespace RusBios\Transport\Event;

interface EventInterface
{
    /**
     * Event constructor.
     * @param array $data
     */
    public function __construct(array $data);

    /**
     * ExchangerEvent static constructor.
     * @param array $data
     * @return EventInterface
     */
    public static function create($data);

    /**
     * @return mixed
     */
    public function getIncrement();

    /**
     * @return array
     */
    public function getArray();

    /**
     * @return mixed
     */
    public function getBody();

    /**
     * @param string $name
     * @return mixed
     */
    public function getItem($name);

    /**
     * @return bool
     */
    public function validation();

    /**
     * @param mixed $result
     * @return EventInterface
     */
    public function setResult($result);

    /**
     * @return mixed
     */
    public function getResult();
}
