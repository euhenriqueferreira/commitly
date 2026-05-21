<?php

namespace App\Enum\Habits;

enum WeekdayEnum: int
{
    case SUNDAY = 0;
    case MONDAY = 1;
    case TUESDAY = 2;
    case WEDNESDAY = 3;
    case THURSDAY = 4;
    case FRIDAY = 5;
    case SATURDAY = 6;

    public function label(): string
    {
        return match ($this) {
            self::SUNDAY => 'Domingo',
            self::MONDAY => 'Segunda-feira',
            self::TUESDAY => 'Terça-feira',
            self::WEDNESDAY => 'Quarta-feira',
            self::THURSDAY => 'Quinta-feira',
            self::FRIDAY => 'Sexta-feira',
            self::SATURDAY => 'Sábado',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::SUNDAY => 'Dom',
            self::MONDAY => 'Seg',
            self::TUESDAY => 'Ter',
            self::WEDNESDAY => 'Qua',
            self::THURSDAY => 'Qui',
            self::FRIDAY => 'Sex',
            self::SATURDAY => 'Sáb',
        };
    }
}
