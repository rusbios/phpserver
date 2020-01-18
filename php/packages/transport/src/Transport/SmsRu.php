<?php

namespace RusBios\Transport\Transport;

use Exception;
use RusBios\Transport\Event\RestEvent;
use RusBios\Transport\Event\SmsRuEvent;

class SmsRu extends Rest
{
    const TYPE = 'smsru';
    const TIMEOUT = 30;
    const MAX_PER_SHIPMENT = 100;
    const URL = 'https://sms.ru/sms/send';

    const REQUEST_CODES_SUCCESS = [100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110];

    /** @var string $apiKey */
    protected $apiKey;

    /** @var SmsRuEvent[] */
    protected $events;

    /**
     * @param string|null $apiKey
     * @return TransportInterface
     */
    public function setConfig($apiKey = null)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @param SmsRuEvent $event
     * @return bool
     */
    public function addEvent(SmsRuEvent $event)
    {
        if (
            count($this->events) < self::MAX_PER_SHIPMENT
            && $event->validation()
            && !array_search($event->getIncrement(), array_keys($this->events))
        ) {
            $this->events[$event->getIncrement()] = $event;
            return true;
        }
        return false;
    }

    /**
     * @return bool|null
     * @throws Exception
     */
    public function send()
    {
        $body = $this->makeBody();
        if (!$body) return null;

        $body['api_id'] = $this->apiKey;
        $body['json'] = 1;

        $response = $this->make(new RestEvent([
            RestEvent::ITEM_URL => self::URL,
            RestEvent::ITEM_METHOD => RestEvent::METHOD_POST,
            RestEvent::ITEM_AUTH => $this->apiKey,
            RestEvent::ITEM_CONTENT_TYPE => 'application/json',
            RestEvent::ITEM_TIMEOUT => self::TIMEOUT,
            RestEvent::ITEM_BODY => json_encode($body),
        ]));

        if (empty($response)) {
            throw new Exception('Is not response');
        } elseif (array_search($response->getStatusCode(), self::REQUEST_CODES_SUCCESS)) {
            return $this->responseProcessing(json_decode($response->getBody(), true));
        }
        throw new Exception(sprintf('Server error code %s', $response->getStatusCode()));
    }

    /**
     * @return array
     */
    protected function makeBody()
    {
        $body = [];
        foreach ($this->events as $phone => $event) {
            $body["to[$phone]"] = $event->getBody();
        }
        return $body;
    }

    /**
     * @param array $response
     * @return bool
     */
    protected function responseProcessing(array $response)
    {
        $success = true;
        foreach ($response['sms'] as $phone => $result) {
            $this->events[$phone]->setResult($result);
            if (array_search($result['status_code'], self::REQUEST_CODES_SUCCESS)) $success = false;
        }
        return $success;
    }
}
