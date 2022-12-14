<?php

namespace Lepr\Patterns\Strategy;

class LowQuack implements QuackInterface
{
    public function quack()
    {
        echo 'хули так тихо';
    }
}