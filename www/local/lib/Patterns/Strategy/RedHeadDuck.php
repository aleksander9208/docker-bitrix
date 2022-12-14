<?php

namespace Lepr\Patterns\Strategy;

class RedHeadDuck extends Duck
{
    public function __construct()
    {
        $this->setFly(new FlyWithoutWings());

        $this->setQuack(new HightQuack());
    }
}