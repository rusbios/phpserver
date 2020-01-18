<?php

namespace RusBios\Transport\Transport;

use Exception;
use RusBios\Transport\Event\RestEvent;

class Telegramm extends Rest
{
    const TYPE = 'telegramm';
    const URL = 'https://api.telegram.org/bot%s/%s';
    const TIMEOUT = 10;

    /** @var string $token */
    protected $token;

    /**
     * @param string|null $token
     * @return TransportInterface
     */
    public function setConfig($token = null)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function send()
    {
        if (!$this->events) return false;

        $result = true;
        foreach ($this->events as &$event) {
            $response = $this->make(new RestEvent([
                RestEvent::ITEM_URL => sprintf(self::URL, $this->token, $event->getMethodName()),
                RestEvent::ITEM_METHOD => RestEvent::METHOD_POST,
                RestEvent::ITEM_AUTH => $this->token,
                RestEvent::ITEM_CONTENT_TYPE => 'application/json',
                RestEvent::ITEM_TIMEOUT => self::TIMEOUT,
                RestEvent::ITEM_BODY => json_encode($event->getBody()),
            ]));

            if ($response->getStatusCode() == 200) {
                $event->setResult(json_decode($response->getBody(), true));
            } else {
                $event->setResult(sprintf(
                    'code response %s and message: %s',
                    $response->getStatusCode(),
                    $response->getBody()
                ));
                $result = false;
            }
        }
        return $result;
    }
}
