<?php

declare(strict_types=1);

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arActivityDescription = [
    'NAME' => Loc::getMessage('EWS_CREATE_EVENT_NAME'),
    'DESCRIPTION' => Loc::getMessage('EWS_CREATE_EVENT_DESCR'),
    'TYPE' => 'activity',
    'CLASS' => 'EwsUpdateEventActivity',
    'JSCLASS' => 'BizProcActivity',
    'CATEGORY' => [
        'ID' => 'other',
    ],
    'RETURN' => [
        'Result' => [
            'NAME' => Loc::getMessage('EWS_CREATE_EVENT_RESULT'),
            'TYPE' => 'array',
        ],
    ],
];