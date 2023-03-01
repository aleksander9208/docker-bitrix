<?php

declare(strict_types=1);

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\LoaderException;
use Lepr\DTO\ExchangeCreateEventDTO;
use Lepr\Ews\Event\EwsEventActivity;
use Lepr\Service\ExchangeService;

/**
 * Активити на создания событий в EWS Outlook
 *
 * Class CBPEwsCreateEventActivity
 */
class CBPEwsCreateEventActivity extends EwsEventActivity
{
    /**
     * @return void
     * @throws LoaderException
     * @throws Exception
     */
    public function Execute()
    {
        parent::Execute();

        if ($this->members) {
            $members = array_diff(explode('; ', trim($this->members)), []);
        }

        $rootActivity = $this->GetRootActivity();
        $documentId = $rootActivity->GetDocumentId();
        $count = $rootActivity->GetVariable('count10');

        //TODO после обновление БП по командировкам,
        // изменить логику работы на требуемый.
        if ($count) {
            for ($ind = 1; $ind <= $count; $ind++) {
                $i = $ind;

                if ($i === 1) {
                    $i = '';
                }

                $subject = $rootActivity->GetVariable('purpose' . $i);

                if (empty($subject)) {
                    break;
                }

                $dateStart = $rootActivity->GetVariable('meeting_date' . $i);
                $dateEnd = $rootActivity->GetVariable('end_date_meeting' . $i);

                $event = $this->getCreateEventDTO($members, $subject, $dateStart, $dateEnd,);

                $result = (new ExchangeService($this->employee))->createEvent($event);
            }
        } else {
            $event = $this->getCreateEventDTO($members);

            $result = (new ExchangeService($this->employee))->createEvent($event);
        }

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

    /**
     * Формирует DTO события
     *
     * @param array|null $members
     * @param string $subject
     * @param string $dataStart
     * @param string $dataEnd
     * @return ExchangeCreateEventDTO
     * @throws Exception
     */
    public function getCreateEventDTO(
        array $members = null,
        string $subject = '',
        string $dataStart = '',
        string $dataEnd = ''
    ): ExchangeCreateEventDTO
    {
        return new ExchangeCreateEventDTO([
              'combined' => $this->combined,
              'subject' => $subject ?: $this->subject,
              'body' => $this->body,
              'date_start' => $dataStart ?: $this->dateStart,
              'date_end' => $dataEnd ?: $this->dateEnd,
              'time_start' => $this->timeStart,
              'time_end' => $this->timeEnd,
              'status' => $this->status,
              'location' => $this->location,
              'members' => $members,
          ]);
    }
}