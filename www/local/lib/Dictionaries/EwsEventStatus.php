<?php

declare(strict_types=1);

namespace Lepr\Dictionaries;

use InvalidArgumentException;
use PrettyBx\Support\Base\AbstractDictionary;

/**
 * Словарь статусов для событий EWS Outlook
 *
 * Class EwsEventStatus
 * @package Zebrains\Portal\Dictionaries
 */
class EwsEventStatus extends AbstractDictionary
{
    /**
     * @return string[]
     */
    public static function getItems(): array
    {
        return [
            'Free' => 'Свободен',
            'Tentative' => 'Под вопросом',
            'Busy' => 'Занят',
            'OOF' => 'Нет на месте',
            'WorkingElsewhere' => 'Работа в другом месте',
            'NoData' => 'Нет данных',
        ];
    }

    /**
     * Возвращает ключ из списка
     *
     * @param   string  $code
     * @return  string
     */
    public static function getKey(string $code): string
    {
        if (!array_key_exists($code, static::getItems())) {
            throw new InvalidArgumentException('Запрошенный элемент ' . $code . ' отсутствует в словаре ' . __CLASS__);
        }

        return $code;
    }
}