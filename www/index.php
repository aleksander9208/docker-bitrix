<?php

use Lepr\Patterns\Factory\PhoneFactory;
use Lepr\Patterns\Prototype\Shape;
use Lepr\Patterns\Singelton\Singelton;
use Lepr\Patterns\Strategy\RedHeadDuck;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

//Фабричный метод
$cell = (new PhoneFactory)->createCellPhone();
$smart = (new PhoneFactory)->createSmartPhone();
$cell->call();
echo '<br>';
$smart->call();
echo '<br>';
echo '<br>';

//Стратегия
$redDuck = new RedHeadDuck();
$redDuck->fly();
echo '<br>';
$redDuck->quack();
echo '<br>';
echo '<br>';

//Сингелтон
$singelton = new Singelton();
$singelto1n = new Singelton();
echo '<pre>';
var_dump($singelton->getInstance());
echo '</pre>';

echo '<pre>';
var_dump($singelto1n->getInstance());
echo '</pre>';
echo '<br>';
$singelton->getInstance()->sayHello();
echo '<br>';
echo '<br>';

//Прототип
$shape = new Shape();
$shape->setName('Цвет');
$shape->setColor('Черный');

$green = clone $shape;
$green->setName('Цвет');
$green->setColor('Зеленый');
echo '<pre>';
var_dump($green);
echo '</pre>';
echo '<pre>';
var_dump($shape);
echo '</pre>';
echo '<br>';
echo '<br>';



require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");