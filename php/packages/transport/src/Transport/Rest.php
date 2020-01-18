<?php

namespace RusBios\Transport\Transport;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use RusBios\Transport\Event\RestEvent;

class Rest extends AbstractTransport
{
    const TYPE = 'rest';

    /** @var string $baseUrl */
    protected $baseUrl;

    public function addEvent(RestEvent $event)
    {
        if ($event->validation()) {
            $this->events[] = $event;
            return true;
        }
        return false;
    }

    /**
     * @param RestEvent $event
     * @return mixed //TODO
     * @throws Exception
     */
    public function make(RestEvent $event)
    {
        $client = new Client([
            'exceptions' => false,
            'timeout' => $event->getItem($event::ITEM_TIMEOUT),
        ]);

        $option = [
            RequestOptions::HEADERS => [
                'Content-Type' => $event->getItem($event::ITEM_CONTENT_TYPE) ?: $event::DEFAULT_CONTENT_TYPE,
                'Authorization' => $event->getItem($event::ITEM_AUTH),
            ],
            RequestOptions::BODY => json_encode($event->getBody()),
            RequestOptions::EXPECT => false,
        ];

        $method = $event->getItem($event::ITEM_METHOD);
        $response = $client->$method($event->getItem($event::ITEM_URL), $option);
        if ($response) {
            return $response;
        }

        throw new Exception('empty response');
    }

    /**
     * @param string $baseUrl
     * @return TransportInterface
     */
    public function setConfig($baseUrl = null)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }
}
