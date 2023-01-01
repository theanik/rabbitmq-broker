<?php
require_once realpath("../vendor/autoload.php");
use App\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

try {
    $channel = AMQPConnection::getInstance()->getConnectionChannel();
    $channel->queue_declare('scrapper', false, false, false, false);

    $counter = 1;
    for ($i = 1; $i <= 20; $i++) {
        $data['page'] = $i;
        $news = [];
        for ($j = 0; $j < 3; $j++) {
            $news[] = 'The Great Demo News ' . $counter++;
        }
        $data['data'] = $news;

        $queueData = new AMQPMessage(
            json_encode($data)
        );
        $channel->basic_publish($queueData, '', 'scrapper');
        echo " [x] {$counter} Data scraped done!\n";
        sleep(10);
    }

    $channel->close();
    AMQPConnection::getInstance()->closeConnection();

} catch (Exception $e) {
    echo $e->getMessage();
}




