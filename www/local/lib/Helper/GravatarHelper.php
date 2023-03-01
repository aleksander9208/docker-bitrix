<?php

declare(strict_types=1);

namespace Lepr\Helper;

use aminelch\Gravatar;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\UserTable;
use CFile;
use CUser;

/**
 * Class GravatarHelper
 * @package Zebrains\Portal\Helper
 */
class GravatarHelper
{
    /**
     * Имя картинки для пользователей
     */
    const NO_PHOTO = 'no_photo_user.jpg';

    /**
     * Размер картинки из граватар
     */
    const DEFAULT_SIZE = 2048;

    /**
     * Устанавливаем картинку из gravatar
     * в случае если ее нет то ставим стандартную
     *
     * @param string $email
     * @param int $size
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function setGravatar(string $email, int $size = self::DEFAULT_SIZE)
    {
        $url = (new Gravatar($email))->image() . '?s='. $size .'&d=404';
        if (self::isGravatar404($url)) {
            $url = '/local/images/' . self::NO_PHOTO;
        }

        self::updateUserPhoto($url, $email);
    }

    /**
     * Проверяем картинку на ошибку в
     * параметрах шапки
     *
     * @param string $url
     * @return bool
     */
    public static function isGravatar404(string $url): bool
    {
        $url_headers = @get_headers($url);
        return (int)explode(' ', $url_headers[0])[1] === 404;
    }

    /**
     * Обновляем картинку у пользователя
     *
     * @param string $url
     * @param string $email
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    protected static function updateUserPhoto(string $url, string $email): void
    {
        $arFile = CFile::MakeFileArray($url);
        if(!strpos($arFile['name'], '.jpg')) {
            $arFile['name'] .= '.jpg';
        }
        $user = UserTable::getList([
            'select' => ['ID', 'PERSONAL_PHOTO'],
            'filter' => ['=EMAIL' => $email, '=ACTIVE' => 'Y'],
        ])->fetch();

        if(is_array($user)){
            self::userUpdate($user['ID'], $user['PERSONAL_PHOTO'], $arFile);
        }
    }

    /**
     * Проверка на обновление картинки
     * и передачи ее на обновление
     *
     * @param int $id
     * @param int|null $currentPhoto
     * @param array $newPhoto
     */
    protected static function userUpdate(int $id, ?int $currentPhoto, array $newPhoto): void
    {
        $arFileUser = CFile::GetFileArray($currentPhoto);

        if (!$currentPhoto || $arFileUser['ORIGINAL_NAME'] === self::NO_PHOTO) {
            self::userSetPhoto($id, $newPhoto);
            return;
        }

        if ($arFileUser['ORIGINAL_NAME'] === $newPhoto['name'] &&
            $arFileUser['WIDTH'] === self::DEFAULT_SIZE
        ) {
            return;
        }

        if ($arFileUser['ORIGINAL_NAME'] !== $newPhoto['name'] &&
            $arFileUser['WIDTH'] !== self::DEFAULT_SIZE
        ) {
            self::userSetPhoto($id, $arFileUser);
            return;
        }

        self::userSetPhoto($id, $newPhoto);
    }

    /**
     * Обновление пользователя
     *
     * @param int $id
     * @param array $arFile
     * @return bool
     */
    protected static function userSetPhoto(int $id, array $arFile): bool
    {
        return (bool)(new CUser())->Update($id, ['PERSONAL_PHOTO' => $arFile]);
    }
}