<?php

namespace RusBios\Transport\Transport;

use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use Psr\Log\LoggerInterface;
use RusBios\Transport\Event\RMQEvent;
use RusBios\Transport\Listener\ListenRMQInterface;

class RabbitMQ extends AbstractTransport implements TransportInterface
{
    const TYPE = 'rmq';
    const TIMEOUT = 5;
    const DEFAULT_PRIORITY = 5;

    protected $host;
    protected $user;
    protected $password;
    protected $port;
    protected $vhost;

    /**
     * @var AMQPStreamConnection $connection
     */
    protected $connection;

    /**
     * @var AMQPChannel $channel
     */
    protected $channel;

    /**
     * Обьявленные очереди
     * @var array $exchanges
     */
    protected $exchanges = [];

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function setConfig($host, $user, $password, $port, $vhost)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->port = $port;
        $this->vhost = $vhost;
        return $this;
    }

    /**
     * @param LoggerInterface $logger
     * @return TransportInterface
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return AMQPStreamConnection
     * @throws NoRabbitMQService
     */
    public function getConnection()
    {
        if (!isset($this->connection)) {
            $this->connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->password, $this->vhost);
        }
        return $this->connection;
    }

    /**
     * @return AMQPChannel
     */
    public function getChannel()
    {
        if (!isset($this->channel)) {
            $this->channel = $this->getConnection()->channel();
        }
        return $this->channel;
    }

    /**
     * @param RMQEvent $event
     * @return bool
     */
    public function make($event)
    {
        return $this->sendRMQMessage(
            new AMQPMessage(json_encode($event->getArray()), [
                'content_type' => 'application/json',
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                'priority' => $event->getItem($event::ITEM_PRIORITY) ?: static::DEFAULT_PRIORITY,
            ]),
            $event->getItem($event::ITEM_TYPE),
            sprintf('%s.%s', $event->getItem($event::ITEM_SOURCE), $event->getItem($event::ITEM_ACTION))
        );
    }

    /**
     * @param AMQPMessage $message
     * @param $exchange
     * @param $routingKey
     * @return bool
     */
    protected function sendRMQMessage(AMQPMessage $message, $exchange, $routingKey)
    {
        $this->declareExchange($exchange);
        if ($this->getChannel()->is_open()) {
            $this->getChannel()->basic_publish($message, $exchange, $routingKey);
            return true;
        }
        return false;
    }

    /**
     * @param string $queue
     * @param array $configListener
     * @param ListenRMQInterface $listener
     * @param AMQPTable $arguments
     * @return void
     * @throws Exception
     */
    public function listen($queue, array $configListener, $listener, AMQPTable $arguments = null)
    {
        $this->getChannel()->queue_declare($queue, false, true, false, false, false, $arguments ?: []);

        foreach ($configListener as $item) {
            $this->declareExchange($item['exchange']);
            $this->getChannel()->queue_bind($queue, $item['exchange'], $item['routing_key']);
        }

        while (true) {
            if (!$this->getChannel()->is_open()) {
                throw new Exception('separation of the channel with the RabbitMQ');
            }
            $message = $this->getChannel()->basic_get($queue);
            if (!empty($message)) {
                $event = json_decode($message->body, true);
                try {
                    $result = $listener->addEvent($event, $message->delivery_info['message_count'] == 0);
                    if ($this->logger && is_array($result)) {
                        $this->logger->info('result listen event', $result);
                    }
                    $this->getChannel()->basic_ack($message->delivery_info['delivery_tag']);
                } catch (Exception $e) {
                    if ($this->logger) {
                        $this->logger->error($e->getMessage(), $event);
                    }
                }
            } else {
                if ($listener->completeExecution()) {
                    break;
                }
                sleep(self::TIMEOUT);
            }
        }
    }

    /**
     * @param string $exchange
     */
    protected function declareExchange($exchange)
    {
        if (!in_array($exchange, $this->exchanges)) {
            $this->getChannel()->exchange_declare($exchange, 'topic', false, true, false);
            $this->exchanges[] = $exchange;
        }
    }
}
