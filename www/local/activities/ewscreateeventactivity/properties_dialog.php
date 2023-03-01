<?php

declare(strict_types=1);

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Lepr\Dictionaries\EwsEventStatus;

?>
<tr>
    <td align='right' width='40%'>
        <span class='adm-required-field'><?= Loc::getMessage('FIELD_EMPLOYEE') ?>:</span>
    </td>
    <td width='60%'>
        <?= CBPDocument::ShowParameterField(
            'string',
            'employee',
            $arCurrentValues['employee'])
        ?>
    </td>
</tr>
<tr>
    <td align='right' width='40%'>
        <span class='adm-required-field'><?= Loc::getMessage('FIELD_TITLE') ?>:</span>
    </td>
    <td width='60%'>
        <?= CBPDocument::ShowParameterField(
            'string',
            'subject',
            $arCurrentValues['subject'])
        ?>
    </td>
</tr>
<tr>
    <td align='right' width='40%'><?= Loc::getMessage('FIELD_COMMENT') ?>:</td>
    <td width='60%'>
        <?= CBPDocument::ShowParameterField(
            'string',
            'body',
            $arCurrentValues['body'])
        ?>
    </td>
</tr>
<tr>
    <td align='right' width='40%'>
        <span class='adm-required-field'><?= Loc::getMessage('FIELD_DATA_START') ?>:</span>
    </td>
    <td width='60%'>
        <?= CBPDocument::ShowParameterField(
            'string',
            'dateStart',
            $arCurrentValues['dateStart'])
        ?>
    </td>
</tr>
<tr>
    <td align='right' width='40%'>
        <span class='adm-required-field'><?= Loc::getMessage('FIELD_DATA_END') ?>:</span>
    </td>
    <td width='60%'>
        <?= CBPDocument::ShowParameterField(
            'string',
            'dateEnd',
            $arCurrentValues['dateEnd'])
        ?>
    </td>
</tr>
<tr>
    <td align='right' width='40%'>
        <span><?= Loc::getMessage('FIELD_COMBINED') ?>:</span>
    </td>
    <td width='60%'>
        <?= CBPDocument::ShowParameterField(
            'bool',
            'combined',
            $arCurrentValues['combined'])
        ?>
    </td>
</tr>
<tr>
    <td align='right' width='40%'>
        <span><?= Loc::getMessage('FIELD_TIME_START') ?>:</span>
    </td>
    <td width='60%'>
        <?= CBPDocument::ShowParameterField(
            'string',
            'timeStart',
            $arCurrentValues['timeStart'])
        ?>
    </td>
</tr>
<tr>
    <td align='right' width='40%'>
        <span><?= Loc::getMessage('FIELD_TIME_END') ?>:</span>
    </td>
    <td width='60%'>
        <?= CBPDocument::ShowParameterField(
            'string',
            'timeEnd',
            $arCurrentValues['timeEnd'])
        ?>
    </td>
</tr>
<tr>
    <td align='right' width='40%'><?= Loc::getMessage('FIELD_STATUS') ?>:</td>
    <td width='60%'>
        <?= CBPDocument::ShowParameterField(
            'string',
            'status',
            $arCurrentValues['status'])
        ?>
    </td>
</tr>
<tr>
    <td></td>
    <td class='ews-status-list'>
        <span><?= Loc::getMessage('FIELD_STATUS_DESCRIPTION') ?></span>
        <ol>
            <?php foreach (EwsEventStatus::getItems() as $status => $value) { ?>
                <ul>
                    <?= $status ?> - <?= $value ?>
                </ul>
            <?php } ?>
        </ol>
    </td>
</tr>
<tr>
    <td align='right' width='40%'><?= Loc::getMessage('FIELD_LOCATION') ?>:</td>
    <td width='60%'>
        <?= CBPDocument::ShowParameterField(
            'string',
            'location',
            $arCurrentValues['location'])
        ?>
    </td>
</tr>
<tr>
    <td align='right' width='40%'><?= Loc::getMessage('FIELD_MEMBERS') ?>:</td>
    <td width='60%'>
        <?= CBPDocument::ShowParameterField(
            'date',
            'members',
            $arCurrentValues['members'])
        ?>
    </td>
</tr>