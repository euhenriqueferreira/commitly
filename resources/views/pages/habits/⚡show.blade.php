<?php

use Livewire\Component;
use App\Enum\Habits\HabitTypeEnum;
use App\Models\Habit;
use App\Enum\Habits\WeekdayEnum;
use Carbon\Carbon;

new class extends Component
{
    public Habit $habit;
    
    public string $currentStreak = '';
    public array $monthlyCompletions = [];
    public string $completionRate = '';
    
    public array $weekdays = [];
    public string $reminderTime = '';
    public string $endsAt = '';

    public function mount(Habit $habit): void
    {
        $this->habit = $habit->load([
            'currentVersion.category',
            'currentVersion.weekdays',
            'completions',
        ]);

        $this->currentStreak = $this->habit->current_streak;
        $this->monthlyCompletions = $this->monthlyCompletions();
        $this->completionRate = $this->completionRate();
        $this->weekdays = $habit->currentVersion->weekdays->map(fn($day) => $day->weekday->shortLabel())->toArray();
        $this->reminderTime = $habit->currentVersion->reminder_time ? Carbon::parse($habit->currentVersion->reminder_time)->format('H:i') : 'Sem lembrete';
        $this->endsAt = $habit->currentVersion->ends_at ? Carbon::parse($habit->currentVersion->ends_at)->format('d/m/Y') : 'Sem limite';
    }

    public function markAsDone(): void
    {
        $today = now()->timezone(auth()->user()->timezone ?? config('app.timezone'))->toDateString();

        if ($this->alreadyCompletedToday()) return;

        $this->habit->completions()->create([
            'completion_date' => $today,
            'completed_at' => now(),
        ]);

        $this->habit->refresh();
    }

    public function unmarkAsDone(): void
    {
        $today = now()->timezone(auth()->user()->timezone ?? config('app.timezone'))->toDateString();

        $this->habit
            ->completions()
            ->whereDate('completion_date', $today)
            ->delete();

        $this->habit->refresh();
    }

    public function alreadyCompletedToday(): bool
    {
        $today = now()->timezone(auth()->user()->timezone ?? config('app.timezone'))->toDateString();
        return $this->habit->completions()->whereDate('completion_date', $today)->exists();
    }

    public function shouldBeCompletedToday(): bool
    {
        $weekday = now()->timezone(auth()->user()->timezone ?? config('app.timezone'))->dayOfWeek;
        return $this->habit->currentVersion->weekdays->contains(fn ($weekdayModel) => $weekdayModel->weekday->value === $weekday);
    }

    public function completionRate(): int
    {
        $start = $this->habit
            ->created_at
            ->copy()
            ->startOfDay();

        $today = now()->startOfDay();

        $expected = 0;
        $completed = 0;

        while ($start <= $today) {

            $version = $this->habit->versionForDate($start);

            if (! $version) {
                $start->addDay();
                continue;
            }

            $weekday = $start->dayOfWeek;

            $shouldExecute = $version
                ->weekdays
                ->contains(fn ($weekdayModel) => $weekdayModel->weekday->value === $weekday);

            if (! $shouldExecute) {
                $start->addDay();
                continue;
            }

            $expected++;

            $hasCompletion = $this->habit
                ->completions()
                ->whereDate('completion_date', $start->toDateString())
                ->exists();

            if ($hasCompletion) {
                $completed++;
            }

            $start->addDay();
        }

        if ($expected === 0) {
            return 0;
        }

        return min(100, (int) round(($completed / $expected) * 100));
    }

    public function monthlyCompletions(): array
    {
        $now = now();

        $currentMonthCompletions = $this->habit
            ->completions()
            ->whereYear('completion_date', $now->year)
            ->whereMonth('completion_date', $now->month)
            ->count();
        
        if($currentMonthCompletions > 0 || $now->day > 7) {
            return [
                'count' => $currentMonthCompletions,
                'month' => 'current'
            ];
        }

        $pastMonthCompletions = $this->habit
            ->completions()
            ->whereYear('completion_date', $now->copy()->subMonth()->year)
            ->whereMonth('completion_date', $now->copy()->subMonth()->month)
            ->count();

        return [
            'count' => $pastMonthCompletions,
            'month' => 'past'
        ];
    }

    public function heatmap(): array
    {
        $start = now()->startOfWeek()->subWeeks(11);

        $end = now()->endOfWeek();

        $completedDates = $this->habit
            ->completions()
            ->whereBetween('completion_date', [$start->toDateString(), $end->toDateString()])
            ->pluck('completion_date')
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->toArray();

        $habitWeekdays = $this->habit
            ->currentVersion
            ->weekdays
            ->pluck('weekday')
            ->map(fn ($weekday) => $weekday->value)
            ->toArray();

        $weeks = [];

        $cursor = $start->copy();

        while ($cursor <= $end) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $date = $cursor->copy();

                $isToday = $date->isToday();
                $isBeforeCreation = $date->lt($this->habit->created_at->startOfDay());
                $shouldComplete = in_array($date->dayOfWeek, $habitWeekdays);
                $completed = in_array($date->toDateString(), $completedDates);

                $week[] = [
                    'date' => $date->toDateString(),
                    'completed' => $completed,
                    'shouldComplete' => $shouldComplete,
                    'isFuture' => $date->isFuture(),
                    'isBeforeCreation' => $isBeforeCreation,
                    'isToday' => $isToday,
                ];

                $cursor->addDay();
            }

            $weeks[] = $week;
        }

        return $weeks;
    }
};
?>

<div class="contents">
    <x-structure.page-header title="Visualizar hábito" back="{{ route('habits.index') }}" />


    <div class="w-full px-2 py-1 rounded-lg text-center" style="background-color: {{ HabitTypeEnum::ATOMIC->color() }}">
        <span class="text-small text-secondary-text">
            {{ HabitTypeEnum::ATOMIC->label() }}
        </span>
    </div>

    @if (! $habit->deleted_at && $habit->isEnded())
        <div class="bg-alert/15 border border-alert px-4 py-2 rounded-lg text-center">
            <span class="text-small text-alert">
                Pausado
            </span>   
        </div>
    @endif

    @if ($habit->deleted_at)
        <div class="bg-danger/15 border border-danger px-4 py-2 rounded-lg text-center">
            <span class="text-small text-danger">
                Inativado
            </span>   
        </div>
    @endif

    {{-- Header --}}
    <div class="bg-background-secondary border border-primary rounded-lg p-4 flex flex-col gap-3">
        <div
            class="w-fit aspect-square p-4 rounded-lg"
            style="background-color: var(--color-{{ $habit->currentVersion->category->color }});" 
        >
            <x-icon 
                icon="{{ $habit->currentVersion->category->icon }}" 
                class="w-6 h-6 object-scale-down text-white" 
            />
        </div>

        <h3 class="text-heading-1 text-primary-text">
            {{ $habit->name }}
        </h3>

        <div class="w-full grid grid-cols-[1fr_1px_1fr_1px_1fr] gap-3 pt-3 border-t border-border">
            <div>
                <h4 class="text-content text-primary-text text-left leading-tight">{{ $habit->current_streak }} dias</h4>
                <span class="text-small text-secondary-text text-left">sequência</span>
            </div>
            <div class="w-full bg-border"></div>
            <div>
                <h4 class="text-content text-primary-text text-left leading-tight">{{ $this->monthlyCompletions['count'] }}x</h4>
                <span class="text-small text-secondary-text text-left">{{ $this->monthlyCompletions['month'] === 'current' ? 'neste mês' : 'mês passado' }}</span>
            </div>
            <div class="w-full bg-border"></div>
            <div>
                <h4 class="text-content text-primary-text text-left leading-tight">{{ $this->completionRate }}%</h4>
                <span class="text-small text-secondary-text text-left">conclusão</span>
            </div>
        </div>
    </div>


    {{-- Infos --}}
    <div class="bg-background-secondary border border-border rounded-lg p-4 flex flex-col gap-2">
        <div class="flex items-center gap-3">
            <p class="flex-1 text-small text-secondary-text text-left">
                Dias da semana
            </p>
            <span class="text-small text-primary-text truncate">
                {{ count($weekdays) === 7 ? 'Todos' : implode(', ', $weekdays) }}
            </span>
        </div>

        <div class="border-t border-border"></div>

        <div class="flex items-center gap-3">
            <p class="flex-1 text-small text-secondary-text text-left">
                Lembrete
            </p>
            <span class="text-small text-primary-text">
                {{ $reminderTime }}
            </span>
        </div>

        <div class="border-t border-border"></div>

        <div class="flex items-center gap-3">
            <p class="flex-1 text-small text-secondary-text text-left">
                Data limite
            </p>
            <span class="text-small text-primary-text">
                {{ $endsAt }}
            </span>
        </div>

        <x-actions.primary-button class="mt-1" href="{{ route('habits.edit', $habit) }}" wire:navigate>
            Editar
        </x-actions.primary-button>
    </div>

    {{-- Heatmap --}}
    <x-structure.section-title>
        Últimas 12 semanas
    </x-structure.section-title>

    <div class="bg-background-secondary border border-primary rounded-lg p-4 flex flex-col gap-3">
        <div
            class="grid grid-cols-13 gap-1"
        >
                {{-- Weekday labels --}}
                @foreach (WeekdayEnum::cases() as $weekday)
                    <div class="flex items-center justify-center">
                        <span class="text-small text-secondary-text">
                            {{ $weekday->digit() }}
                        </span>
                    </div>

                    @foreach ($this->heatmap() as $week)
                        @php
                            $day = $week[$loop->parent->index];
                        @endphp

                        <div class="
                                h-5 w-5 rounded-sm
                                {{  $day['isFuture'] ? 'bg-border/30' : '' }}
                                {{  (! $day['shouldComplete'] || $day['isBeforeCreation'] || $day['isToday']) && ! $day['isFuture'] ? 'bg-border' : '' }}
                                {{  $day['shouldComplete'] && ! $day['completed'] && ! $day['isFuture'] && ! $day['isBeforeCreation'] ? 'bg-danger' : '' }}
                                {{  $day['completed'] ? 'bg-success' : '' }}
                            "
                            title="{{ $day['date'] }}"
                        ></div>
                    @endforeach
                @endforeach
        </div>

        <div class="w-full pt-3 border-t border-border flex gap-3">
            <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-success"></div>
                <span class="text-small text-secondary-text text-left">
                    Feito
                </span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-danger"></div>
                <span class="text-small text-secondary-text text-left">
                    Não feito
                </span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-border"></div>
                <span class="text-small text-secondary-text text-left">
                    Não era pra fazer
                </span>
            </div>
        </div>
    </div>
    
    @if(!$habit->isEnded())
        @if ($this->shouldBeCompletedToday() && ! $this->alreadyCompletedToday())
            <div class="bg-secondary-backgorund border border-border px-4 py-2 rounded-lg text-center">
                <span class="text-small text-primary-text">
                    Ainda não feito hoje
                </span>
            </div>
            <x-actions.primary-button loading="markAsDone" wire:click="markAsDone">
                Marcar como feito hoje
            </x-actions.primary-button>
        @elseif ($this->shouldBeCompletedToday() && $this->alreadyCompletedToday())
        <div class="bg-success/30 border border-success px-4 py-2 rounded-lg text-center">
                <span class="text-small text-primary-text">
                    Já feito hoje
                </span>
            </div>
            <x-actions.outline-button loading="unmarkAsDone" wire:click="unmarkAsDone">
                Não fiz ainda
            </x-actions.outline-button>
        @endif
    @endif

</div>