# RabbitMQ PAD

### Basic Queue Demo
First we to exec to docker container (broker-application).
```
// Go to basic queue folder
cd basic-queue
// Run the producer
php producer.php
// Run the consumer
php consumer.php
```

### Pub/Sub Demo
```
// Go to pub-sub folder
cd pub-sub
// Run the publisher
php publisher.php
// Run the consumer 1
php subscriber1.php
// Run the consumer 2
php subscriber2.php
```