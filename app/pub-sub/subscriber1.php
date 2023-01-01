<?php
require_once realpath("../vendor/autoload.php");
use App\AMQPConnection;

/**
 * This service is responsible sending SMS to newly created users.
 */
try {
    $channel = AMQPConnection::getInstance()->getConnectionChannel();
    $channel->exchange_declare('user_create_pub', 'fanout', false, false, false);
    list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);
    $channel->queue_bind($queue_name, 'user_create_pub');

    echo " [*] Waiting for user. To exit press CTRL+C\n";

    $channel->basic_consume($queue_name, '', false, true, false, false, function ($messageData) {
        $user = json_decode($messageData->body, true);
        echo "[x] Sending Message to " . $user['name'] . PHP_EOL;
        echo "Name : {$user['name']} \n";
        echo "Phone : {$user['phone']} \n";
        echo "Email : {$user['email']} \n";
        echo "Address : {$user['address']} \n";
        echo "[x] Message sent \n\n";
    });

    while ($channel->is_open()) {
        $channel->wait();
    }

    $channel->close();
    AMQPConnection::getInstance()->closeConnection();
} catch (Exception $e) {
    echo $e->getMessage();
}




