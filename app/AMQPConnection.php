<?php
namespace App;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class AMQPConnection {

    /**
     * @var object|null
     */
    private static ?object $instance = null;

    /**
     * @var object|null
     */
    public ?object $connection = null;

    protected function __construct() { }

    /**
     * @return AMQPConnection
     */
    public static function getInstance(): AMQPConnection
    {
        if (self::$instance == null) {
            self::$instance = new AMQPConnection();
        }
        return self::$instance;
    }

    /**
     * @throws \Exception
     */
    public function getConnectionChannel(): \PhpAmqpLib\Channel\AbstractChannel
    {
        $this->connection = new AMQPStreamConnection('rabbitmq', 5672, 'root', 'pass');
        return $this->connection->channel();
    }

    /**
     * @throws \Exception
     */
    public function closeConnection(): void
    {
        $this->connection->close();
    }
}