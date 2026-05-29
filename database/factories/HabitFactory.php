<?php

namespace Database\Factories;

use App\Enum\Habits\HabitTypeEnum;
use App\Models\Habit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends Factory<Habit>
 */
class HabitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => Arr::random(['Treinar', 'Ler 10 páginas', 'Meditar', 'Beber água', 'Estudar Laravel', 'Caminhar', 'Escrever diário', 'Alongar', 'Dormir cedo', 'Programar', 'Estudar inglês', 'Organizar mesa', 'Fazer skincare', 'Tomar vitaminas', 'Revisar tarefas']),
            'type' => HabitTypeEnum::ATOMIC,
        ];
    }
}
