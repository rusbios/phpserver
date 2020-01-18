<?php

namespace RusBios\Transport\Exchange;

use RusBios\Transport\Transport\TransportInterface;

interface ExchangerInterface
{
    /**
     * @param array $config
     * @return ExchangerInterface
     */
    public static function create(array $config);

    /**
     * @param $name
     * @return TransportInterface
     */
    public function getTransport($name);
}
