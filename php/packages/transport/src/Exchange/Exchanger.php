<?php

namespace RusBios\Transport\Exchange;

use Exception;
use RusBios\Transport\Transport;

class Exchanger implements ExchangerInterface
{
    /** @var ExchangerInterface $exchanger */
    protected static $exchanger;

    /** @var Transport\TransportInterface[] $transports */
    protected $transports;

    /** @var array $configs */
    protected $configs;

    /**
     * Exchanger constructor.
     * @param array $configs
     */
    protected function __construct(array $configs)
    {
        $this->configs = $configs;
    }

    /**
     * @param array $config
     * @return ExchangerInterface
     */
    public static function create(array $config)
    {
        if (!static::$exchanger) {
            static::$exchanger = new static($config);
        }
        return static::$exchanger;
    }

    /**
     * @param $name
     * @return Transport\TransportInterface
     * @throws Exception
     */
    public function getTransport($name)
    {
        if (empty($this->transports[$name])) {
            switch ($name) {
                case Transport\Rest::TYPE:
                    $this->transports[$name] = new Transport\Rest();
                    break;
                case Transport\RabbitMQ::TYPE:
                    $this->transports[$name] = new Transport\RabbitMQ();
                    break;
                case Transport\SmsRu::TYPE:
                    $this->transports[$name] = new Transport\SmsRu();
                    break;
                case Transport\Telegramm::TYPE:
                    $this->transports[$name] = new Transport\Telegramm();
                    break;
            }
        }

        if (!empty($this->transports[$name])) {
            return $this->transports[$name];
        }

        throw new Exception('Incorrect transport name');
    }
}
