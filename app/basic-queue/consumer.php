<?php
require_once realpath("../vendor/autoload.php");
use App\AMQPConnection;

try {
    $channel = AMQPConnection::getInstance()->getConnectionChannel();
    $channel->queue_declare('scrapper', false, false, false, false);

    echo " [*] Waiting for data. To exit press CTRL+C\n";

    $channel->basic_consume('scrapper', '', false, true, false, false, function ($msgData) {
        echo " [x] New Data Received \n";

        $data = json_decode($msgData->body, true);
        echo "Page : {$data['page']} \n";
        foreach ($data['data'] as $datum) {
            echo $datum . PHP_EOL;
        }
    });

    while ($channel->is_open()) {
        $channel->wait();
    }
} catch (Exception $e) {
    echo $e->getMessage();
}



