<?php

use Bitrix\Main\Application;
use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Loader;

require_once(Application::getDocumentRoot().'/vendor/autoload.php');

if (Loader::includeModule('yngc0der.rabbitmq')) {
    ServiceLocator::getInstance()->get('rabbitmq.service_loader')->load();
}