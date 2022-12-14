<?php

namespace Lepr\Patterns\Strategy;

class FlyWidthWings implements FlyInterface
{
    public function fly()
    {
        echo "Крылышки";
    }
}