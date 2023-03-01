<?php

declare(strict_types=1);

namespace Lepr\Helper;

use Bitrix\Iblock\Iblock;

/**
 * Class ElementHelper
 * @package Zebrains\Portal\Helper
 */
class ElementHelper
{
    /**
     * Присваиваем ID календаря к
     * свойству элемента для обновления записи
     *
     * @param int $idElement
     * @param string $code
     * @param string|null $id
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function setIdCalendarOutlook(int $idElement, string $code, string $id = null): void
    {
        if($id) {
            $idBlock = IblockHelper::getIBlockIdElement($idElement);

            $contractCodes = Iblock::wakeUp($idBlock)->getEntityDataClass();
            $contractCode = $contractCodes::getList([
                'select' => ['ID'],
                'filter' => ['=ID' => $idElement],
            ])->fetchObject();

            $contractCode->set($code, $id);

            $contractCode->save();
        }
    }
}