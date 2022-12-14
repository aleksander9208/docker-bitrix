<?php
// Абстрактная фабрика - позволяет создавать семейство
// обьектов не привязываясь к конкретным классам создоваемых обьектов

// он имеет в себе функции создать продукты, которые по итогу
// возврщают свои обьекты, которые имеют свои интерфейсы
// В интерфейсах продуктов есть свои данные продуктов
//
// Смысл фабрики в том что мы создаем отдельное семейство классов которое создает для нас отдельное семейство обьектов
//

use Lepr\Patterns\AbstractFactory\Chair;
use Lepr\Patterns\AbstractFactory\Coffee;
use Lepr\Patterns\AbstractFactory\Sofa;

interface FurnitureFactory
{
    public function createChair(): Chair;
    public function createCoffee(): Coffee;
    public function createSofa(): Sofa;
}