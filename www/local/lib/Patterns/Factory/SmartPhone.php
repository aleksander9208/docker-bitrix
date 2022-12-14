<?php

namespace Lepr\Patterns\Factory;

class SmartPhone implements InterfacePhone
{

    public function call()
    {
        echo "Добро пожаловать в будущее";
    }
}