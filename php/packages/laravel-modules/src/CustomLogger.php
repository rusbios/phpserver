<?php

namespace RusBios\Modules;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CustomLogger extends Logger
{
    /**
     * CustomLogger constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        if (!strlen($name)) $name = 'custom';
        $dir = storage_path(sprintf('logs/%s.log', $name));
        $handler = [];
        try {
            $handler[] = new StreamHandler($dir);
        } catch (\Exception $e) {
        }
        parent::__construct($name, $handler, []);
    }
}
