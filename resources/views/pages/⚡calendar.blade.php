<?php

use Livewire\Component;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use App\Services\Habits\GetHabitsForDateService;
use App\Enum\Habits\WeekdayEnum;

new class extends Component
{
    public Carbon $currentMonth;
    public Carbon $selectedDate;
    public Collection $habits;

    public function mount(GetHabitsForDateService $service): void {
        $timezone = auth()->user()->timezone ?? config('app.timezone');
        $this->currentMonth = now($timezone)->startOfMonth();
        $this->selectedDate = now($timezone)->startOfDay();
        $this->loadHabits($service);
    }

    public function loadHabits(GetHabitsForDateService $service): void 
    {
        $this->habits = $service->handle($this->selectedDate);
    }

    public function selectDate(string $date, GetHabitsForDateService $service): void 
    {
        $this->selectedDate = Carbon::parse($date);
        $this->loadHabits($service);
    }

    public function toggleHabit(int $habitId, GetHabitsForDateService $service): void 
    {
        $habit = $this->habits->firstWhere('id', $habitId);

        if (! $habit) return;

        $completion = $habit
            ->completions()
            ->whereDate('completion_date', $this->selectedDate->toDateString())
            ->first();

        if ($completion) {
            $completion->delete();
        } else {
            $habit->completions()
                ->create([
                    'completion_date' => $this->selectedDate->toDateString(),
                    'completed_at' => now(),
                ]);
        }

        $this->loadHabits($service);
    }

    public function calendarDays(): Collection
    {
        $start = $this->currentMonth
            ->copy()
            ->startOfMonth()
            ->startOfWeek();

        $end = $this->currentMonth
            ->copy()
            ->endOfMonth()
            ->endOfWeek();

        return collect(CarbonPeriod::create($start, $end));
    }

    public function weekHabitsCount(GetHabitsForDateService $service): int 
    {
        $start = now()->startOfWeek();
        $end = now()->endOfWeek();

        $count = 0;

        foreach (CarbonPeriod::create($start, $end) as $date) {
            $count += $service->handle($date)->count();
        }

        return $count;
    }

    public function dayMetadata(Carbon $date, GetHabitsForDateService $service): array 
    {
        $habits = $service->handle($date);

        $total = $habits->count();

        if ($total === 0) {
            return [
                'hasHabits' => false,
                'completed' => false,
                'missed' => false,
            ];
        }

        $completed = $habits->filter(fn ($habit) => $habit->completion_for_date)->count();
        $isPast = $date->isPast() && ! $date->isToday();

        return [
            'hasHabits' => true,
            'completed' => $isPast && $completed === $total,
            'missed' => $isPast && $completed < $total,
        ];
    }

    public function previousMonth(): void
    {
        $this->currentMonth = $this->currentMonth
            ->copy()
            ->subMonth();
    }

    public function nextMonth(): void
    {
        $this->currentMonth = $this->currentMonth
            ->copy()
            ->addMonth();
    }
};
?>

<div class="contents">
    {{-- <x-structure.page-header title="{{ str($currentMonth->translatedFormat('F'))->ucfirst() }}" /> --}}
    <x-structure.page-header title="{{ str($currentMonth->translatedFormat('F'))->ucfirst() }} de {{ $currentMonth->year }}">
        <x-actions.nav-action wire:click="previousMonth">
            <x-icon icon="arrow-left" class="w-5 h-5 text-secondary-text object-scale-down" />
        </x-actions.nav-action>
        <x-actions.nav-action wire:click="nextMonth">
            <x-icon icon="arrow-right" class="w-5 h-5 text-secondary-text object-scale-down" />
        </x-actions.nav-action>
    </x-structure.page-header>

    <span class="text-small text-left text-secondary-text">
        {{ $this->weekHabitsCount(app(GetHabitsForDateService::class)) }} itens esta semana
    </span>

     {{-- Calendar --}}
    <div class="bg-background-secondary border border-border rounded-lg p-4 flex flex-col gap-3">

        {{-- Weekdays --}}
        <div class="grid grid-cols-7 gap-1">
            @foreach (WeekdayEnum::cases() as $weekday)
                <div class="text-center">
                    <span class="text-small text-secondary-text">
                        {{ $weekday->digit() }}
                    </span>
                </div>
            @endforeach
        </div>

        {{-- Days --}}
        <div class="grid grid-cols-7 gap-1">
            @foreach ($this->calendarDays() as $date)
                @php
                    $isCurrentMonth = $date->month === $currentMonth->month;
                    $isSelected = $date->isSameDay($selectedDate);
                    $metadata = $this->dayMetadata($date, app(GetHabitsForDateService::class));
                @endphp


                <button wire:click="selectDate('{{ $date->toDateString() }}')" class="flex flex-col items-center gap-0.5 p-1.5 rounded-lg border transition {{ $isSelected ? 'bg-primary/15 border-primary' : 'border-transparent' }}">
                    <span class="
                        text-small text-center
                        {{ $isCurrentMonth ? 'text-primary-text' : '' }}
                        {{ ! $isCurrentMonth ? 'text-secondary-text/40' : '' }}
                        {{ $isCurrentMonth ? 'text-primary-text' : '' }}
                        {{ $metadata['completed'] ? '!text-success' : '' }}
                        {{ $metadata['missed'] ? '!text-danger' : '' }}
                    ">
                        {{ $date->day }}
                    </span>

                    <div class="h-1 w-1 rounded-full bg-primary  {{ $metadata['hasHabits'] ? 'opacity-100' : 'opacity-0' }}"></div>
                </button>
            @endforeach

        </div>
        
        {{-- Legend --}}
        <div class="flex items-center gap-2 pt-3 border-t border-border">
            <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-success"></div>
                <span class="text-small text-secondary-text text-left">
                    Concluído
                </span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-primary"></div>
                <span class="text-small text-secondary-text text-left">
                    Pendente
                </span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-danger"></div>
                <span class="text-small text-secondary-text text-left">
                    Não feito
                </span>
            </div>
        </div>

    </div>

    {{-- Selected Day --}}

    <x-structure.section-title>
        @if ($selectedDate->isToday())
            Hoje - {{ $selectedDate->translatedFormat('d \d\e F') }}
        @else
            {{ $selectedDate->translatedFormat('d \d\e F') }}
        @endif
    </x-structure.section-title>
    
    {{-- Habits --}}
    <div class="w-full flex flex-col gap-2">
        @forelse ($habits as $habit)
            @php
                $completed = $habit->completion_for_date !== null;
            @endphp

            <button wire:click="toggleHabit({{ $habit->id }})" class="bg-background-secondary border border-border rounded-lg p-4 flex items-start gap-3">
                <div class="
                    flex items-center justify-center w-6 h-6 rounded-full border 
                    {{ $completed ? 'bg-success/15 border-success' : '' }}
                    {{ ! $completed && $selectedDate->isPast() && ! $selectedDate->isToday() ? 'border-red-500 text-red-500' : '' }}
                    {{ ! $completed && ! ($selectedDate->isPast() && ! $selectedDate->isToday()) ? 'bg-transparent border-border' : '' }}
                ">
                    @if($completed)
                        <x-icon icon="check" class="w-3 h-3 object-scale-down text-success" />
                    @endif

                    @if(! $completed && $selectedDate->isPast() && ! $selectedDate->isToday())
                        <x-icon icon="x-mark" class="w-3 h-3 object-scale-down text-danger" />
                    @endif
                </div>

                <div class="flex-1 space-y-1">
                    <span class="block text-content text-left text-primary-text {{ $completed ? 'line-through' : '' }}">
                        {{ $habit->name }}
                    </span>
                    <span class="block text-small text-left text-secondary-text">
                        {{ $habit->resolved_version?->category?->name }}
                    </span>
                </div>

                <div class="shrink-0 rounded-lg px-2 py-0.5 bg-secondary-text/15">
                    <span class="text-small text-secondary-text truncate">
                        {{ $habit->type->label() }}
                    </span>
                </div>
            </button>
        @empty
            <div class="bg-transparent border border-dashed border-border rounded-lg p-4">
                <span class="flex justify-center gap-1.5 text-content text-left text-primary-text">
                    <x-icon icon="circle-x-mark" class="w-6 h-6 object-scale-down" />
                    Nenhum hábito para hoje.
                </span>
            </div>
        @endforelse
    </div>
</div>