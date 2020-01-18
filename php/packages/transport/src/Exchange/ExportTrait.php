<?php

namespace RusBios\Transport\Exchange;

use RusBios\Transport\Event\EventInterface;

trait ExportTrait
{
    /** @var ExchangerInterface */
    protected $exchanger;

    /**
     * @param EventInterface $data
     * @param string $transport
     * @return EventInterface[]
     */
    protected function sendDataToErp(EventInterface $data, $transport)
    {
        $transport = $this->exchanger->getTransport($transport);
        if ($transport->addEvent($data)) {
            $transport->send();
        }
        return $transport->getEvents();
    }
}
