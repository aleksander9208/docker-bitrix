<?php
// Одиночка - паттерн с одним классом, создает только
// один глобальный экземпляр класса для всего кода

namespace Lepr\Patterns\Singelton;

class Singelton
{
    private $instance;

    public function getInstance()
    {
        if (empty($instance)) {
            $this->instance = $this;
        }

        return $this->instance;
    }

    public function sayHello()
    {
        echo 'Одиночка привет';
    }
}