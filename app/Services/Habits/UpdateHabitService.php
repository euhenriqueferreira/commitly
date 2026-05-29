<?php

namespace App\Services\Habits;

use App\Models\Habit;
use Illuminate\Support\Facades\DB;

class UpdateHabitService
{
    public function handle(Habit $habit, array $data): Habit
    {
        return DB::transaction(
            function () use ($habit, $data) {
                $habit->currentVersion
                    ->update([
                        'valid_until' => now(),
                    ]);

                $version = $habit
                    ->versions()
                    ->create([
                        'category_id' => $data['category_id'],
                        'reminder_time' => $data['reminder_time'],
                        'ends_at' => $data['ends_at'],
                        'valid_from' => now(),
                        'valid_until' => null,
                    ]);

                foreach ($data['weekdays'] as $weekday) {
                    $version
                        ->weekdays()
                        ->create([
                            'weekday' => $weekday,
                        ]);
                }

                $habit->update([
                    'name' => $data['name'],
                    'current_version_id' => $version->id,
                ]);

                return $habit->fresh([
                    'currentVersion.category',
                    'currentVersion.weekdays',
                ]);
            }
        );
    }
}
