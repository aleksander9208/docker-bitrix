<?php
// Прототип - когда создаем новые обьекты
// класса клонируя какой то другой обьект

namespace Lepr\Patterns\Prototype;

class Shape
{
    private $name;
    private $color;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setColor($color)
    {
        $this->color = $color;
    }

    public function __clone()
    {
        echo 'Клонирование прошло успешно';
    }
}