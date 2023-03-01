<?php

declare(strict_types=1);

namespace Lepr\DTO;

use DateInterval;
use DateTime;

/**
 * Метод обновления события в календаре
 *
 * Class ExchangeUpdateEventDTO
 * @package Zebrains\Portal\DTO
 */
class ExchangeUpdateEventDTO
{
    /** @var string Описания события */
    public string $body;

    /** @var DateTime Дата начало события */
    public DateTime $start;

    /** @var DateTime Дата окончания события */
    public DateTime $end;

    /** @var mixed ID события в Outlook */
    public string $eventId;

    /** @var array Список участников */
    public array $members;

    public function __construct(array $data)
    {
        $this->body = $data['body'];
        $this->start = (new DateTime($data['date_start']))->add(new DateInterval('PT' .$data['time_start']. 'H'));
        $this->end = (new DateTime($data['date_end']))->add(new DateInterval('PT' .$data['time_end']. 'H'));
        $this->eventId = $data['eventId'];
        $this->members = $data['members'] ?? [];
    }
}