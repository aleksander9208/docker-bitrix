<?php

declare(strict_types=1);

namespace Lepr\Helper;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Exception;

/**
 * Class IblockHelper
 * @package Zebrains\Portal\Helper
 */
class IblockHelper
{
    /**
     * Возвращает id инфоблока по его символьному коду
     *
     * @param string $code
     * @param string $type
     *
     * @return int
     * @throws LoaderException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws Exception
     */
    public static function getIBlockIdByCode(string $code, string $type): int
    {
        if (!Loader::includeModule('iblock')) {
            throw new Exception('Модуль информационных блоков не установлен.');
        }

        return (int)IblockTable::getRow([
            'select' => ['ID'],
            'filter' => ['=IBLOCK_TYPE_ID' => $type, '=CODE' => $code],
        ])['ID'];
    }

    /**
     * Возвращаем id инфоблока по его элементу
     *
     * @param int $id
     * @return int
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getIBlockIdElement(int $id): int
    {
        $element = ElementTable::getList([
             'select' => ['IBLOCK_ID'],
             'filter' => ['=ID' => $id],
         ])->fetchObject();

        return $element->getIblockId();
    }
}