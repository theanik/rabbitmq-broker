<?php
require_once realpath("../vendor/autoload.php");
use App\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

try {
    $channel = AMQPConnection::getInstance()->getConnectionChannel();
    $channel->exchange_declare('user_create_pub','fanout', false, false, false);

    $faker = \Faker\Factory::create();
    for ($i = 1; $i <= 20; $i++) {
        $user = [
            'name' => $faker->name,
            'email' => $faker->email,
            'phone' => $faker->phoneNumber,
            'address' => $faker->address,
            'creditCardNumber' => $faker->creditCardNumber
        ];

        $userData = new AMQPMessage(json_encode($user));
        $channel->basic_publish($userData, 'user_create_pub');
        echo "[x] New User created" . PHP_EOL;
        sleep(10);
    }

    $channel->close();
    AMQPConnection::getInstance()->closeConnection();
} catch (Exception $e) {
    echo $e->getMessage();
}




