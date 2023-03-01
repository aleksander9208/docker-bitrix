<?php

declare(strict_types=1);

namespace Lepr\Interfaces;

/**
 * Interface ParseEventResponseInterface
 * @package Zebrains\Portal\Interfaces
 */
interface ParseEventResponseInterface
{
    /**
     * Типизация для $response не указываем
     * так как могут быть разные типы объекта
     *
     * @param $response
     * @return array
     */
    public function parseResponse($response): array;
}