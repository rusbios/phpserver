<?php

namespace RusBios\Transport\Event;

abstract class AbstractEvent implements EventInterface
{
    const TYPE = 'event';
    const INCREMENT = 'id';

    /**
     * @var array
     */
    protected $data;

    /**
     * @var mixed
     */
    protected $result;

    /**
     * EventInterface constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->result = null;
    }

    /**
     * @param array $data
     * @return EventInterface
     */
    public static function create($data)
    {
        return new static($data);
    }

    /**
     * @return mixed
     */
    public function getIncrement()
    {
        return $this->data[static::INCREMENT];
    }

    /**
     * @return array
     */
    public function getArray()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getBody()
    {
        return $this->data;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getItem($name)
    {
        return array_key_exists($name, $this->data) ? $this->data[$name] : null;
    }

    /**
     * @param mixed $result
     * @return EventInterface
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    public function validation()
    {
        return $this->data[static::INCREMENT];
    }
}
