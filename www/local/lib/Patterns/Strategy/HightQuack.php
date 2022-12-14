<?php

namespace Lepr\Patterns\Strategy;

class HightQuack implements QuackInterface
{
    public function quack()
    {
        echo 'Ебать как громко';
    }
}