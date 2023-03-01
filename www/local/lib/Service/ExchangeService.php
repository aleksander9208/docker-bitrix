<?php

declare(strict_types=1);

namespace Lepr\Service;

use Bitrix\Main\Config\Option;
use Exception;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfItemChangeDescriptionsType;
use jamesiarmes\PhpEws\Enumeration\CalendarItemUpdateOperationType;
use jamesiarmes\PhpEws\Enumeration\ConflictResolutionType;
use jamesiarmes\PhpEws\Request\UpdateItemType;
use jamesiarmes\PhpEws\Type\ItemChangeType;
use jamesiarmes\PhpEws\Type\ItemIdType;
use Lepr\DTO\ExchangeCreateEventDTO;
use Lepr\DTO\ExchangeUpdateEventDTO;
use Lepr\Ews\Event\Actions\CreateEventAction;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfBaseFolderIdsType;
use jamesiarmes\PhpEws\Client;
use jamesiarmes\PhpEws\Enumeration\DistinguishedFolderIdNameType;
use jamesiarmes\PhpEws\Type\ConnectingSIDType;
use jamesiarmes\PhpEws\Type\DistinguishedFolderIdType;
use jamesiarmes\PhpEws\Type\EmailAddressType;
use jamesiarmes\PhpEws\Type\ExchangeImpersonationType;
use jamesiarmes\PhpEws\Type\TargetFolderIdType;
use Lepr\Ews\Event\Actions\UpdateEventAction;

/**
 * Сервис для взаимодействия с Exchange
 *
 * Class ExchangeService
 * @package Itech\Service
 */
class ExchangeService
{
    /** @var Client */
    protected Client $ews;

    /** @var string Почта пользователя от которого создается событие */
    protected string $email;

    /** @var string Модуль с настройками доступа EWS */
    public const MODULE_ID = '';

    /** @var string Настройки доступов в EWS */
    protected string $access;

    /** @var string Константа с уровнем прав доступа олицетворение */
    public const IMPERSONATION = 'impersonation';

    /** @var string Константа с уровнем прав доступа делегирование */
    public const DELEGATION = 'delegation';

    /**
     * ExchangeService constructor.
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->ews = new Client(
            Option::get(self::MODULE_ID, 'ews_services_url'),
            Option::get(self::MODULE_ID, 'ews_services_user'),
            Option::get(self::MODULE_ID, 'ews_services_password'),
            Client::VERSION_2016
        );
        $this->access = Option::get(self::MODULE_ID, 'ews_services_type');
        $this->email = $email;
        $this->setImpersonation();
    }

    /**
     * Возвращаем параметры клиента
     *
     * @return Client
     */
    protected function getClient(): Client
    {
        return $this->ews;
    }

    /**
     * Представляет учетную запись для олицетворения
     * при использовании SOAP ExchangeImpersonation
     */
    public function setImpersonation(): void
    {
        if ($this->access === self::IMPERSONATION) {
            $impersonationType = new ExchangeImpersonationType();
            $sid = new ConnectingSIDType();
            $sid->PrimarySmtpAddress = $this->email;
            $impersonationType->ConnectingSID = $sid;

            $this->ews->setImpersonation($impersonationType);
        }
    }

    /**
     * Добавляем пользователя с делегированным доступом
     * при использовании SOAP ExchangeImpersonation
     * Типизацию не указываем, так как разный тип
     * должен принимать
     *
     * @param $request
     */
    protected function setDelegation($request): void
    {
        if ($this->access === self::DELEGATION) {
            $request->ParentFolderIds = new NonEmptyArrayOfBaseFolderIdsType();
            $roomFolder = new DistinguishedFolderIdType();
            $roomFolder->Id = DistinguishedFolderIdNameType::CALENDAR;
            $roomFolder->Mailbox = new EmailAddressType();
            $roomFolder->Mailbox->EmailAddress = $this->email;
            $target = new TargetFolderIdType();
            $target->DistinguishedFolderId = $roomFolder;
            $request->SavedItemFolderId = $target;
            $request->ParentFolderIds->DistinguishedFolderId[] = $roomFolder;
        }
    }

    /**
     * Создаем событие в календаре
     *
     * @param ExchangeCreateEventDTO $data
     * @return array
     * @throws Exception
     */
    public function createEvent(ExchangeCreateEventDTO $data): array
    {
        $action = new CreateEventAction();
        $request = $action->getRequest();

        $event = $action->getEvent($data->start, $data->end, $data);

        if ($data->members) {
            $action->setAttendees($data, $event);
        }

        $this->setDelegation($request);

        $request->Items->CalendarItem[] = $event;

        $response = $this->getClient()->CreateItem($request);

        return $action->parseResponse($response);
    }

    /**
     * Обновления события в календаре
     *
     * @param ExchangeUpdateEventDTO $data
     * @return array
     */
    public function updateEvent(ExchangeUpdateEventDTO $data): array
    {
        $action = new UpdateEventAction();
        $request = new UpdateItemType();
        $request->ConflictResolution = ConflictResolutionType::ALWAYS_OVERWRITE;
        $request->SendMeetingInvitationsOrCancellations = CalendarItemUpdateOperationType::SEND_TO_ALL_AND_SAVE_COPY;

        $change = new ItemChangeType();
        $change->ItemId = new ItemIdType();
        $change->ItemId->Id = $data->eventId;
        $change->Updates = new NonEmptyArrayOfItemChangeDescriptionsType();

        $action->updateDates($change, $data->start, $data->end);

        $action->updateAttendees($change, $data);
        $action->updateBody($change, $data->body);
        $request->ItemChanges[] = $change;

        $response = $this->getClient()->UpdateItem($request);

        return $action->parseResponse($response);
    }
}