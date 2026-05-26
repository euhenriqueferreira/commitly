<?php

namespace App\Services\Habits;

use Carbon\Carbon;
use App\Models\Habit;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class GetHabitsForDateService
{
    public function handle(Carbon $date): Collection {
        $weekday = $date->dayOfWeek;

        $habits = Habit::query()
            ->where('user_id', Auth::user()->id)
            ->whereNull('deleted_at')
            /* Load current valid version */
            ->with([
                'versions.category',
                'versions.weekdays',
                'completions' => fn ($query) => $query->whereDate('completion_date', $date->toDateString()),
            ])
            ->get();

            return $habits->map(function ($habit) use ($date) {
                $version = $habit->versionForDate($date);

                if (! $version || ! $version->category) return null;
                $completion = $habit->completions->first(fn ($completion) => $completion->completion_date->isSameDay($date));

                $habit->setRelation('resolved_version', $version);
                $habit->setRelation('completion_for_date', $completion);

                return $habit;
            })
            ->filter()
            ->filter(function ($habit) use ($weekday) {
                return $habit
                    ->resolved_version
                    ->weekdays
                    ->contains('weekday', $weekday);
            })
            ->values();
    }
}