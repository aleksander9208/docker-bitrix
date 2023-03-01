<?php

declare(strict_types=1);

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Lepr\DTO\ExchangeUpdateEventDTO;
use Lepr\Ews\Event\EwsEventActivity;
use Lepr\Service\ExchangeService;

/**
 * Активити на обновления событий в EWS Outlook
 *
 * Class CBPEwsCreateEventActivity
 */
class CBPEwsUpdateEventActivity extends EwsEventActivity
{
    /**
     * @return void
     */
    public function Execute()
    {
        if ($this->members) {
            $members = array_diff(explode('; ', trim($this->members)), []);
        }

        $rootActivity = $this->GetRootActivity();
        $documentId = $rootActivity->GetDocumentId();

        $eventId = $this->getIdCalendar($documentId);

        $event = new ExchangeUpdateEventDTO([
            'eventId' => $eventId,
            'body' => $this->body,
            'date_start' => $this->dateStart,
            'date_end' => $this->dateEnd,
            'time_start' => $this->timeStart,
            'time_end' => $this->timeEnd,
            'members' => $members,
        ]);

        $result = (new ExchangeService($this->employee))->updateEvent($event);

        $this->setResultService($result, $documentId[2]);
    }

    /**
     * @param array $documentType
     * @param string $activityName
     * @param array $arWorkflowTemplate
     * @param array $arWorkflowParameters
     * @param array $arWorkflowVariables
     * @param array|null $arCurrentValues
     * @param string $formName
     * @return false|string|null
     */
    public static function getPropertiesDialog (
        array $documentType,
        string $activityName,
        array $arWorkflowTemplate,
        array $arWorkflowParameters,
        array $arWorkflowVariables,
        array $arCurrentValues = null,
        string $formName = ''
    )
    {
        if (!is_array($arCurrentValues)) {
            $arCurrentValues = self::getPropertyList();

            $arCurrentActivity = &CBPWorkflowTemplateLoader::FindActivityByName($arWorkflowTemplate, $activityName);
            if (is_array($arCurrentActivity['Properties'])) {
                $arCurrentValues = array_merge($arCurrentValues, $arCurrentActivity['Properties']);
            }
        }

        return CBPRuntime::GetRuntime()->ExecuteResourceFile(
            __FILE__,
            'properties_dialog.php',
            [
                'arCurrentValues' => $arCurrentValues,
                'formName' => $formName,
            ]
        );
    }
}