<?php

namespace Lepr\Patterns\Factory;

class CellPhone implements InterfacePhone
{

    public function call()
    {
        echo "Привет из 2000-ных";
    }
}