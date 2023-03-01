<?php

declare(strict_types=1);

namespace Lepr\Events;

use Bitrix\Main\Entity\{EntityError, Event, EventResult};

/**
 * Класс для работы с событиями отправки писем
 */
class MailSendEvent
{
    /**
     * Блокировка отправки писем на почту
     *
     * @param Event $event
     *
     * @return EventResult|void
     */
    public function sendMailBlocked(Event $event)
    {
        $fields = $event->getParameter("fields");

        if (
            $fields['EVENT_NAME'] === 'IM_NEW_NOTIFY'
            && (int)$fields['C_FIELDS']['FROM_USER_ID'] === 0
            && $fields['C_FIELDS']['MESSAGE_50'] === 'Почта'
        ) {
            $result = new EventResult();
            $result->addError(new EntityError('Пропускаем добавление записи в таблицу b_event'));

            return $result;
        }
    }
}
