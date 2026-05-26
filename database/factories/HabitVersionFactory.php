<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Habit;
use App\Models\HabitVersion;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HabitVersion>
 */
class HabitVersionFactory extends Factory
{
public function definition(): array
    {
        $createdAt = Carbon::now()->subWeeks(fake()->numberBetween(2, 12));

        return [
            'reminder_time' => fake()->boolean(70) ? fake()->time('H:i') : null,
            'valid_from' => $createdAt,
            'ends_at' => fake()->boolean(20) ? $createdAt ->copy()->addMonths(fake()->numberBetween(1, 6)) : null,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
