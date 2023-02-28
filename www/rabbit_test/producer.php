<?php

use Bitrix\Main\DI\ServiceLocator;
use PhpAmqpLib\Message\AMQPMessage;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$msg = [
    'user' => random_int(0, 100),
    'message' => 'Фигля мугля писос калдос мукалтос',
    'timestamp' => microtime(true),
];

$producer = ServiceLocator::getInstance()
    ->get('rabbitmq.rabbit_test_producer');
$producer->publish(json_encode($msg));

echo "Сообщение отправлено";