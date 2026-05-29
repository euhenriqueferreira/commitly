<?php

namespace App\Enum\Habits;

enum HabitTypeEnum: string
{
    case ATOMIC = 'atomic';

    public function label(): string
    {
        return match ($this) {
            self::ATOMIC => 'Atômico',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ATOMIC => '#8B949E15',
        };
    }
}
