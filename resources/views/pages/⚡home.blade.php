<?php

use Livewire\Component;
use App\Services\Habits\GetHabitsForDateService;
use Carbon\Carbon;

new class extends Component
{
    public array $habits = [];

    public function mount(GetHabitsForDateService $service): void {
        $this->loadHabits($service);
    }

    public function loadHabits(GetHabitsForDateService $service): void {
        $this->habits = $service
            ->handle(Carbon::today(auth()->user()->timezone ?? config('app.timezone')))
            ->all();
    }

    public function toggleHabit(int $habitId): void {
        $habit = collect($this->habits)->firstWhere('id', $habitId);

        if (! $habit) return;

        $today = now()
            ->timezone(auth()->user()->timezone ?? config('app.timezone'))
            ->toDateString();

        $completion = $habit
            ->completions()
            ->whereDate('completion_date', $today)
            ->first();

        if ($completion) {
            $completion->delete();
        } else {
            $habit->completions()
                ->create([
                    'completion_date' => $today,
                    'completed_at' => now(),
                ]);
        }

        $this->loadHabits(app(GetHabitsForDateService::class));
    }
};
?>

<div class="contents">
    <x-structure.page-header title="Olá, {{ auth()->user()->name }}!">
        <x-actions.nav-action class="relative">
            <x-icon icon="bell" class="w-5 h-5 text-secondary-text object-scale-down" />
            <div class="w-2 h-2 rounded-full bg-danger absolute z-10 right-1.5 top-1.5"></div>
        </x-actions.nav-action>

        <x-actions.nav-action class="bg-primary/15 !border-primary ">
            <span class="text-xs text-primary leading-tight font-mono">
                {{ Str::of(auth()->user()->name)->substr(0, 3)->upper() }}
            </span>
        </x-actions.nav-action>
    </x-structure.page-header>

    <x-actions.primary-button href="{{ route('calendar') }}" wire:navigate>
        <x-icon icon="calendar" class="w-5 h-5 text-primary-text object-scale-down" />
        Acessar calendário
    </x-actions.primary-button>

    <x-structure.section-title>
        Hábitos de hoje
    </x-structure.section-title>
    
    {{-- Habits --}}
    <div class="w-full flex flex-col gap-2">
        @forelse ($habits as $habit)
            @php
                $completed = $habit->completions->isNotEmpty();
            @endphp

            <button wire:click="toggleHabit({{ $habit->id }})" class="bg-background-secondary border border-border rounded-lg p-4 flex items-start gap-3">
                <div class="flex items-center justify-center w-6 h-6 rounded-full border {{ $completed ? 'bg-success/15 border-success' : 'bg-transparent border-border' }}">
                    @if($completed)
                        <x-icon icon="check" class="w-3 h-3 object-scale-down text-success" />
                    @endif
                </div>

                <div class="flex-1 space-y-1">
                    <span class="block text-content text-left text-primary-text">
                        {{ $habit->name }}
                    </span>
                    <span class="block text-small text-left text-secondary-text">
                        {{ $habit->resolved_version->category->name}}
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