<?php

declare(strict_types=1);

namespace Lepr\Ews\Event\Actions;

use DateTime;
use Lepr\DTO\ExchangeCreateEventDTO;
use Lepr\Interfaces\ParseEventResponseInterface;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfAllItemsType;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfAttendeesType;
use jamesiarmes\PhpEws\Enumeration\BodyTypeType;
use jamesiarmes\PhpEws\Enumeration\CalendarItemCreateOrDeleteOperationType;
use jamesiarmes\PhpEws\Enumeration\ResponseClassType;
use jamesiarmes\PhpEws\Enumeration\RoutingType;
use jamesiarmes\PhpEws\Enumeration\SensitivityChoicesType;
use jamesiarmes\PhpEws\Request\CreateItemType;
use jamesiarmes\PhpEws\Type\AttendeeType;
use jamesiarmes\PhpEws\Type\BodyType;
use jamesiarmes\PhpEws\Type\CalendarItemType;
use jamesiarmes\PhpEws\Type\EmailAddressType;

/**
 * Создание событий в EWS Outlook
 *
 * Class CreateEventAction
 * @package Zebrains\Portal\Ews\Event\Actions
 */
class CreateEventAction implements ParseEventResponseInterface
{
    /**
     * Добавляем событие в календаре
     *
     * @param DateTime $start
     * @param DateTime $end
     * @param ExchangeCreateEventDTO $data
     * @return CalendarItemType
     */
    public function getEvent(DateTime $start, DateTime $end, ExchangeCreateEventDTO $data): CalendarItemType
    {
        $event = new CalendarItemType();
        $event->RequiredAttendees = new NonEmptyArrayOfAttendeesType();
        $event->Start = $start->format('c');
        $event->End = $end->format('c');
        $event->Subject = $data->subject;
        $event->Body = new BodyType();
        $event->Body->_ = $data->body;
        $event->Body->BodyType = BodyTypeType::TEXT;
        $event->Sensitivity = SensitivityChoicesType::NORMAL;
        $event->LegacyFreeBusyStatus = $data->status;

        return $event;
    }

    /**
     * Формируем запрос по календаре
     *
     * @return CreateItemType
     */
    public function getRequest(): CreateItemType
    {
        $request = new CreateItemType();
        $request->SendMeetingInvitations = CalendarItemCreateOrDeleteOperationType::SEND_TO_NONE;
        $request->Items = new NonEmptyArrayOfAllItemsType();

        return $request;
    }

    /**
     * Если нужно добавить участников к событию
     *
     * @param ExchangeCreateEventDTO $data
     * @param CalendarItemType $event
     */
    public function setAttendees(ExchangeCreateEventDTO $data, CalendarItemType $event): void
    {
        foreach ($data->members as $member) {
            $attendee = new AttendeeType();
            $attendee->Mailbox = new EmailAddressType();
            $attendee->Mailbox->EmailAddress = $member;
            $attendee->Mailbox->RoutingType = RoutingType::SMTP;

            $event->RequiredAttendees->Attendee[] = $attendee;
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