<?php

namespace Lepr\Message\Rabbit;

use Yngc0der\RabbitMq\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitTest implements ConsumerInterface
{

    public function execute(AMQPMessage $msg)
    {
        echo ' [x] Получено сообщение ', $msg->body, "\n <br>";
    }

}