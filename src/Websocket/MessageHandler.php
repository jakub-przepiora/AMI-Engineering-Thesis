<?php
namespace App\Websocket;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

use Monolog\Logger;
use Psr\Log\LoggerInterface;

class MessageHandler implements MessageComponentInterface
{

    protected $connections;
    private $logger;

    public function __construct()
    {
        $this->connections = new SplObjectStorage;
//        $this->logger = $logger;
//        $this->logger->info('New connection');

    }
    public function onConnect(ConnectionInterface $connection)
    {
        $this->logger->info('New connection');
        $connection->send('Hello from the server');
    }


    public function onOpen(ConnectionInterface $conn)
    {
        $this->connections->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach($this->connections as $connection)
        {
            if($connection === $from)
            {
                continue;
            }

            $connection->send($msg);
        }
    }
    public function onTest(ConnectionInterface $from, $msg)
    {

    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->connections->detach($conn);
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        $this->connections->detach($conn);
        $conn->close();
    }

}