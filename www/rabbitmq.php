<?php

use Bitrix\Main\DI\ServiceLocator;
use PhpAmqpLib\Exception\AMQPTimeoutException;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

echo 'Проверка rabbitmq <br>';

$consumer = ServiceLocator::getInstance()
    ->get('rabbitmq.rabbit_test_consumer');
$consumer->consume(5);
