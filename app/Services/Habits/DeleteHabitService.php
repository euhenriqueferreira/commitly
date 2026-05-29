<?php

namespace App\Services\Habits;

use App\Models\Habit;

class DeleteHabitService
{
    public function handle(Habit $habit): void 
    {
        $habit->delete('');
    }
}