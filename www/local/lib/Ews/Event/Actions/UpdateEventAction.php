<?php

declare(strict_types=1);

namespace Lepr\Ews\Event\Actions;

use DateTime;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfAttendeesType;
use jamesiarmes\PhpEws\Enumeration\ResponseClassType;
use jamesiarmes\PhpEws\Enumeration\RoutingType;
use jamesiarmes\PhpEws\Enumeration\UnindexedFieldURIType;
use jamesiarmes\PhpEws\Type\AttendeeType;
use jamesiarmes\PhpEws\Type\BodyType;
use jamesiarmes\PhpEws\Type\CalendarItemType;
use jamesiarmes\PhpEws\Type\EmailAddressType;
use jamesiarmes\PhpEws\Type\ItemChangeType;
use jamesiarmes\PhpEws\Type\PathToUnindexedFieldType;
use jamesiarmes\PhpEws\Type\SetItemFieldType;
use Lepr\DTO\ExchangeUpdateEventDTO;
use Lepr\Interfaces\ParseEventResponseInterface;

/**
 * Обновление событий в EWS Outlook
 *
 * Class UpdateEventAction
 * @package Zebrains\Portal\Ews\Event\Actions
 */
class UpdateEventAction implements ParseEventResponseInterface
{
    /**
     * Обновляем даты у события
     *
     * @param ItemChangeType $change
     * @param DateTime $dateStart
     * @param DateTime $dateEnd
     */
    public function updateDates(ItemChangeType $change, DateTime $dateStart, DateTime $dateEnd): void
    {
        if ($dateStart) {
            $field = new SetItemFieldType();
            $field->FieldURI = new PathToUnindexedFieldType();
            $field->FieldURI->FieldURI = UnindexedFieldURIType::CALENDAR_START;
            $field->CalendarItem = new CalendarItemType();
            $field->CalendarItem->Start = $dateStart->format('c');
            $change->Updates->SetItemField[] = $field;
        }

        if ($dateEnd) {
            $field = new SetItemFieldType();
            $field->FieldURI = new PathToUnindexedFieldType();
            $field->FieldURI->FieldURI = UnindexedFieldURIType::CALENDAR_END;
            $field->CalendarItem = new CalendarItemType();
            $field->CalendarItem->End = $dateEnd->format('c');
            $change->Updates->SetItemField[] = $field;
        }
    }

    /**
     * Обновляем участников события
     *
     * @param ItemChangeType $change
     * @param ExchangeUpdateEventDTO $data
     */
    public function updateAttendees(ItemChangeType $change, ExchangeUpdateEventDTO $data): void
    {
        if ($data->members) {
            $field = new SetItemFieldType();
            $field->FieldURI = new PathToUnindexedFieldType();
            $field->FieldURI->FieldURI = UnindexedFieldURIType::CALENDAR_REQUIRED_ATTENDEES;
            $field->CalendarItem = new CalendarItemType();
            $field->CalendarItem->RequiredAttendees = new NonEmptyArrayOfAttendeesType();

            foreach ($data->members as $member) {
                $attendee = new AttendeeType();
                $attendee->Mailbox = new EmailAddressType();
                $attendee->Mailbox->EmailAddress = $member;
                $attendee->Mailbox->RoutingType = RoutingType::SMTP;
                $field->CalendarItem->RequiredAttendees->Attendee[] = $attendee;
            }
            $change->Updates->SetItemField[] = $field;
        }
    }

    /**
     * Обновляем описание события
     *
     * @param ItemChangeType $change
     * @param string $body
     */
    public function updateBody(ItemChangeType $change, string $body): void
    {
        if ($body) {
            $field = new SetItemFieldType();
            $field->FieldURI = new PathToUnindexedFieldType();
            $field->FieldURI->FieldURI = UnindexedFieldURIType::ITEM_BODY;
            $field->CalendarItem = new CalendarItemType();
            $field->CalendarItem->Body = new BodyType();
            $field->CalendarItem->Body->_ = $body;
            $change->Updates->SetItemField[] = $field;
        }
    }

    /**
     * Парсим результат от Exchange
     *
     * @param $response
     * @return array
     */
    public function parseResponse($response): array
    {
        $responseMessages = $response->ResponseMessages->CreateItemResponseMessage;

        $events = [];
        foreach ($responseMessages as $responseMessage) {
            $events['status'] = ResponseClassType::SUCCESS;

            if ($responseMessage->ResponseClass !== ResponseClassType::SUCCESS) {
                $events['code'] = $responseMessage->ResponseCode;
                $events['message'] = $responseMessage->MessageText;
                $events['status'] = ResponseClassType::ERROR;
            }

            foreach ($responseMessage->Items->CalendarItem as $key => $item) {
                $events['Id'] = $item->ItemId->Id;
                $events['ChangeKey'] = $item->ItemId->ChangeKey;
            }
        }

        return $events;
    }
}