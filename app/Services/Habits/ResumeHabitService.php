<?php

namespace App\Services\Habits;

use App\Models\Habit;
use App\Models\HabitVersion;
use Illuminate\Support\Facades\DB;

class ResumeHabitService
{
    public function handle(
        Habit $habit
    ): HabitVersion {

        return DB::transaction(
            function () use ($habit) {
                $current = $habit->currentVersion;

                $current->update([
                    'valid_until' => today(),
                ]);

                $version = HabitVersion::query()
                    ->create([
                        'habit_id' => $habit->id,
                        'category_id' => $current->category_id,
                        'reminder_time' => $current->reminder_time,
                        'valid_from' => today(),
                        'ends_at' => null,
                    ]);

                foreach ($current->weekdays as $weekday) {
                    $version->weekdays()
                        ->create([
                            'weekday' => $weekday->weekday,
                        ]);
                }

                $habit->update([
                    'current_version_id' => $version->id,
                ]);

                return $version;
            }
        );
    }
}
