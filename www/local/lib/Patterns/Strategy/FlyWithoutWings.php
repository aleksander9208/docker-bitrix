<?php

namespace Lepr\Patterns\Strategy;

class FlyWithoutWings implements FlyInterface
{
    public function fly()
    {
        echo 'Инвалид без крыльев';
    }
}