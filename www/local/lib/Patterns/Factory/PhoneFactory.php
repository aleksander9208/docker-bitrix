<?php
// Фабричный метод - паттерн, который определяет интерфейс в суперклассе,
// позволяя подклассам изменять тип создаваемых обьектов

namespace Lepr\Patterns\Factory;

class PhoneFactory
{
    public function createCellPhone(): InterfacePhone
    {
        return new CellPhone;
    }

    public function createSmartPhone(): InterfacePhone
    {
        return new SmartPhone;
    }
}