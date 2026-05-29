<?php

namespace App\Services\Habits;

use App\Models\Habit;

class EndHabitService
{
    public function handle(Habit $habit): void
    {
        $habit
            ->currentVersion
            ->update([
                'ends_at' => today(),
                'valid_until' => today(),
            ]);
    }
}
