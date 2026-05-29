<?php

namespace App\Services\Habits;

use App\Enum\Habits\HabitTypeEnum;
use App\Models\Habit;
use App\Models\HabitVersion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateHabitService
{
    public function handle(array $data): Habit
    {
        return DB::transaction(function () use ($data) {
            $habit = Habit::create([
                'user_id' => Auth::user()->id,
                'name' => $data['name'],
                'type' => HabitTypeEnum::ATOMIC,
            ]);

            $version = HabitVersion::create([
                'habit_id' => $habit->id,
                'category_id' => $data['category_id'],
                'reminder_time' => $data['reminder_time'],
                'ends_at' => $data['ends_at'],
                'valid_from' => now(),
            ]);

            $habit->update([
                'current_version_id' => $version->id,
            ]);

            foreach ($data['weekdays'] as $weekday) {
                $version->weekdays()->create([
                    'weekday' => $weekday,
                ]);
            }

            return $habit;
        });
    }
}
