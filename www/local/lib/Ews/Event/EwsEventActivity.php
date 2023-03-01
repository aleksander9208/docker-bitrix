<?php

declare(strict_types=1);

namespace Lepr\Ews\Event;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Bizproc\FieldType;
use Bitrix\Main\Application;
use Bitrix\Main\Diag\FileLogger;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use CBPActivity;
use CBPActivityExecutionStatus;
use CBPTrackingType;
use CBPWorkflowTemplateLoader;
use CBPWorkflowTemplateUser;
use DateTime;
use jamesiarmes\PhpEws\Enumeration\ResponseClassType;
use Bitrix\Main\IO;
use Lepr\Helper\ElementHelper;

/**
 * Общий класс для работы с EWS
 *
 * Class EwsEventActivity
 */
class EwsEventActivity extends CBPActivity
{
    /** @var string Имя файла для логов */
    public const LOG_FILE = '/local/log/EwsEventActivity.log';

    /** @var int Размер файлов лога */
    public const MAX_LOG_SIZE = 5;

    /** @var string ID записи календаря в ИБ */
    public const ID_POST_CALENDAR = 'ID_CALENDAR_OUTLOOK';

    /**
     * CBPEwsEventActivity constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->arProperties = self::getPropertyList();
        $this->SetPropertiesTypes(self::getPropertyListType());
    }

    /**
     * @return int
     * @throws LoaderException
     */
    public function Execute()
    {
        if (!Loader::includeModule('crm') || !Loader::includeModule('itech.crm')) {
            return CBPActivityExecutionStatus::Closed;
        }
    }

    /**
     * Записываем ошибки если есть и
     * записываем ID записи в календаре
     *
     * @param array $result
     * @param string $documentId
     *
     * @return int
     */
    public function setResultService(array $result, string $documentId): int
    {
        $fileErrorPath = Application::getDocumentRoot() . self::LOG_FILE;
        if (!IO\File::isFileExists($fileErrorPath)) {
            self::isFile($fileErrorPath);
        }

        if ($result['status'] === ResponseClassType::ERROR) {
            $rootActivity = $this->GetRootActivity();
            $rootActivity->SetVariable('ews_create', 1);

            $message = (new DateTime())->format("d-m-Y H:i:s") . ' ' . $result['code'] . ' ' . $result['message'];

            $logger = new FileLogger($fileErrorPath, self::MAX_LOG_SIZE);
            $logger->error($message, $result);

            $this->WriteToTrackingService($result['code'] . ' ' . $result['message'], 0, CBPTrackingType::Error);
        }

        $documentId = (int)$documentId;

        ElementHelper::setIdCalendarOutlook($documentId, self::ID_POST_CALENDAR, $result['Id']);

        return CBPActivityExecutionStatus::Executing;
    }

    /**
     * Передаваемые поля для записи значений в активити
     *
     * @return array
     */
    public static function getPropertyList(): array
    {
        return [
            'combined' => false,
            'subject' => '',
            'body' => '',
            'dateStart' => '',
            'dateEnd' => '',
            'timeStart' => '0',
            'timeEnd' => '23',
            'status' => '',
            'location' => '',
            'members' => '',
            'employee' => '',
        ];
    }

    /**
     * Тип полей для записи и вывода в активити
     *
     * @return array[]
     */
    public static function getPropertyListType(): array
    {
        return [
            'combined' => [
                'Type' => FieldType::BOOL,
            ],
            'body' => [
                'Type' => FieldType::STRING,
            ],
            'subject' => [
                'Type' => FieldType::STRING,
            ],
            'dateStart' => [
                'Type' => FieldType::STRING,
            ],
            'dateEnd' => [
                'Type' => FieldType::STRING,
            ],
            'timeStart' => [
                'Type' => FieldType::STRING,
            ],
            'timeEnd' => [
                'Type' => FieldType::STRING,
            ],
            'status' => [
                'Type' => FieldType::STRING,
            ],
            'location' => [
                'Type' => FieldType::STRING,
            ],
            'members' => [
                'Type' => FieldType::STRING,
            ],
            'employee' => [
                'Type' => FieldType::STRING,
            ],
        ];
    }

    /**
     * @param array $documentType
     * @param string $activityName
     * @param array $arWorkflowTemplate
     * @param array $arWorkflowParameters
     * @param array $arWorkflowVariables
     * @param array $arCurrentValues
     * @param array $arErrors
     * @return bool
     */
    public static function getPropertiesDialogValues (
        array $documentType,
        string $activityName,
        array &$arWorkflowTemplate,
        array &$arWorkflowParameters,
        array &$arWorkflowVariables,
        array $arCurrentValues,
        array &$arErrors
    ): bool
    {
        $arProperties = [
            'combined' => $arCurrentValues['combined'],
            'subject' => $arCurrentValues['subject'],
            'body' => $arCurrentValues['body'],
            'dateStart' => $arCurrentValues['dateStart'],
            'dateEnd' => $arCurrentValues['dateEnd'],
            'timeStart' => $arCurrentValues['timeStart'],
            'timeEnd' => $arCurrentValues['timeEnd'],
            'status' => $arCurrentValues['status'],
            'location' => $arCurrentValues['location'],
            'members' => $arCurrentValues['members'],
            'employee' => $arCurrentValues['employee'],
        ];

        $arErrors = self::validateProperties(
            $arProperties,
            new CBPWorkflowTemplateUser(CBPWorkflowTemplateUser::CurrentUser)
        );

        if (count($arErrors) > 0) {
            return false;
        }

        $arCurrentActivity = &CBPWorkflowTemplateLoader::FindActivityByName(
            $arWorkflowTemplate,
            $activityName
        );

        $arCurrentActivity['Properties'] = $arProperties;

        return true;
    }

    /**
     * Создаем файл для хранения ошибок
     *
     * @param string $fileError
     */
    public static function isFile(string $fileError): void
    {
        IO\Directory::createDirectory(Application::getDocumentRoot(). '/local/log/');

        file_put_contents($fileError, '', FILE_APPEND);
    }

    /**
     * Возвращаем ID календаря в Outlook
     *
     * @param array $documentId
     * @return string
     */
    public function getIdCalendar(array $documentId): string
    {
        $documentService = $this->workflow->GetService("DocumentService");
        $fieldType = $documentService->GetDocument($documentId);

        return $fieldType['PROPERTY_' . self::ID_POST_CALENDAR];
    }
}