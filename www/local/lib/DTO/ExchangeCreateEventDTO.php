<?php

declare(strict_types=1);

namespace Lepr\DTO;

use DateInterval;
use DateTime;
use Exception;
use Lepr\Dictionaries\EwsEventStatus;

/**
 * Метод создание шаблона события в календаре
 *
 * Class ExchangeCreateEventDTO
 * @package Zebrains\Portal\DTO
 */
class ExchangeCreateEventDTO
{
    /** @var string Заголовок события */
    public string $subject;

    /** @var string Комментарий события */
    public string $body;

    /** @var DateTime Дата начало события */
    public DateTime $start;

    /** @var DateTime Дата окончания события */
    public DateTime $end;

    /** @var array Массив участников события */
    public array $members;

    /** @var string Статус события */
    public string $status;

    /** @var string Место события */
    public string $location;

    /**
     * ExchangeCreateEventDTO constructor.
     *
     * @param array $data
     * @throws Exception
     */
    public function __construct(array $data)
    {
        $this->subject = $data['subject'];
        $this->body = $data['body'];
        $this->start = (new DateTime($data['date_start']))->add(new DateInterval('PT' .$data['time_start']. 'H'));
        $this->end = (new DateTime($data['date_end']))->add(new DateInterval('PT' .$data['time_end']. 'H'));
        $this->status = $data['status'] ?? EwsEventStatus::getKey('Busy');
        $this->location = $data['location'];
        $this->members = $data['members'];

        if ($data['combined'] === 'Y') {
            $this->start = new DateTime($data['date_start']);
            $this->end = new DateTime($data['date_end']);
        }
    }
}