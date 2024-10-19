<?php

namespace App\Enum;

enum PriorityEnum : string
{
    case Low = 'Low';
    case Medium = 'Medium';
    case High = 'High';
    case Urgent = 'Urgent';

    public static function values(): array
    {
        return array_column(PriorityEnum::cases(), 'name');
    }
}
