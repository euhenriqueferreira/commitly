<?php

namespace Database\Seeders;

use App\Enum\Habits\WeekdayEnum;
use App\Models\Category;
use App\Models\Habit;
use App\Models\HabitCompletion;
use App\Models\HabitVersion;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HabitSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->each(fn (User $user) => $this->createHabitsForUser($user));
    }

    private function createHabitsForUser(User $user): void 
    {
        $habitCount = fake()->numberBetween(5, 12);

        for ($i = 0; $i < $habitCount; $i++) {
            DB::transaction(function () use ($user) {
                $habit = Habit::factory()->create(['user_id' => $user->id,]);
                $version = HabitVersion::factory()->create([
                    'habit_id' => $habit->id,
                    'category_id' => Category::query()->inRandomOrder('')->first()?->id,
                ]);
                // $habit->update(['current_version_id' => $version->id]);

                $weekdays = collect(WeekdayEnum::cases())->shuffle()->take(fake()->numberBetween(1, 7));

                foreach ($weekdays as $weekday) {
                    $version->weekdays()->create(['weekday' => $weekday->value]);
                }

                $this->generateCompletions($habit, $version, $weekdays);
            });
        }
    }

    private function generateCompletions(Habit $habit, HabitVersion $version, $weekdays): void 
    {
        $start = $version->valid_from->copy()->startOfDay();

        $end = now()->startOfDay();

        foreach (CarbonPeriod::create($start, $end) as $date) {
            if ($version->ends_at && $date->gt($version->ends_at)) break;

            $shouldRun = $weekdays->contains(fn ($weekday) => $weekday->value === $date->dayOfWeek);

            if (! $shouldRun) continue;

            if (! fake()->boolean(80)) continue;

            HabitCompletion::query()
                ->create([
                    'habit_id' => $habit->id,
                    'completion_date' => $date->toDateString(),
                    'completed_at' => $date->copy()->setTime(fake()->numberBetween(6, 23), fake()->numberBetween(0,59)),
                ]);
        }
    }
}
