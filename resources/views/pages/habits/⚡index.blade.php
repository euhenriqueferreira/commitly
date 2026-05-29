<?php

use Livewire\Component;
use Carbon\Carbon;
use Livewire\WithPagination;
use App\Models\Habit;
use App\Models\Category;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public array $categories = [];
    public string $status = 'active';

    public array $statuses = [];

    public function mount(): void
    {
        $this->statuses = [
            ['value' => 'all', 'label' => 'Todos'],
            ['value' => 'active', 'label' => 'Ativos'],
            ['value' => 'ended', 'label' => 'Pausados'],
            ['value' => 'inactive', 'label' => 'Inativados'],
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategories(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function toggleCategory(int $categoryId): void 
    {
        if (in_array($categoryId, $this->categories)) {
            $this->categories = array_values(array_filter($this->categories, fn ($id) => $id !== $categoryId));
            return;
        }

        $this->categories[] = $categoryId;

        $allIds = Category::query()->pluck('id')->toArray();

        sort($allIds);

        $selected = $this->categories;
        sort($selected);

        if ($selected === $allIds) $this->categories = [];
    }

    public function selectAllCategories(): void
    {
        $this->categories = [];
    }

    public function habits()
    {
        return Habit::query()
            ->withTrashed()
            ->where('user_id', auth()->id())
            ->with([
                'currentVersion.category',
                'completions' => fn ($query) => $query->latest('completion_date'),
            ])
            ->when(
                filled($this->search),
                fn ($query) => 
                    $query->where('name', 'like', "%{$this->search}%")
            )
            ->when(
                count($this->categories) > 0,
                fn ($query) => 
                    $query->whereHas('currentVersion', fn ($query) => $query->whereIn('category_id', $this->categories))
            )
            ->when(
                $this->status === 'active',
                fn ($query) =>
                    $query
                        ->whereNull('deleted_at')
                        ->whereHas('currentVersion',
                            fn ($query) => $query->where(
                                        fn ($query) => $query
                                                ->whereNull('ends_at')
                                                ->orWhereDate('ends_at', '>=', today())
                                    )
                        )
            )
            ->when(
                $this->status === 'ended',
                fn ($query) =>
                    $query
                        ->whereNull('deleted_at')
                        ->whereHas(
                            'currentVersion',
                            fn ($query) => $query
                                    ->whereDate('ends_at', '<', today())
                        )
            )
            ->when(
                $this->status === 'inactive',
                fn ($query) =>
                    $query->whereNotNull('deleted_at')
            )
            ->latest()
            ->paginate(15);
    }

    public function categoriesList()
    {
        return Category::query()
            ->orderBy('name')
            ->get();
    }

    public function lastCompletionLabel(Habit $habit): string 
    {
        $completion = $habit
            ->completions
            ->first();

        if (! $completion) return 'Nunca';

        $date = $completion->completion_date;

        if ($date->isToday()) return 'Hoje';
        if ($date->isYesterday()) return 'Ontem';

        return 'Há ' . (ceil($date->diffInDays(now()))) . ' dias';
    }
};
?>

<div class="contents">
    <x-structure.page-header title="Hábitos Atômicos" />

    <div class="flex gap-2">
        <x-actions.primary-button href="{{ route('calendar') }}" wire:navigate>
            <x-icon icon="calendar" class="w-5 h-5 text-primary-text object-scale-down" />
            Calendário
        </x-actions.primary-button>

        <x-actions.primary-button href="{{ route('habits.create') }}" wire:navigate>
            <x-icon icon="plus" class="w-5 h-5 text-primary-text object-scale-down" />
            Novo hábito
        </x-actions.primary-button>
    </div>

    {{-- Filters --}}
    <div class="space-y-3">
        <x-form.input-text wireModel="search" placeholder="Buscar hábitos..." autocomplete="email" wireType="live" isRequired />

        {{-- Categories --}}
        <div class="bg-background-secondary border border-border rounded-lg p-4 space-y-2">
            <label class="block text-small text-left text-primary-text">
                Categoria
            </label>
            <div class="w-full flex no-wrap gap-2 overflow-x-auto no-scrollbar">
                <button
                    wire:click="selectAllCategories"
                    class="px-4 py-2 rounded-lg shrink-0 border border-primary {{ count($categories) === 0 ? 'bg-primary' : 'bg-transparent' }}">   
                    <span class="text-small  text-center {{ count($categories) === 0 ? 'text-primary-text' : 'text-primary' }}">
                        Todos
                    </span>
                </button>

                @foreach ($this->categoriesList() as $category)
                    <button
                        wire:click="toggleCategory({{ $category->id }})"
                        class="px-4 py-2 rounded-lg shrink-0 border border-primary transition {{ count($categories) === 0 ? 'bg-primary' : 'bg-transparent' }}"
                        style="
                            border-color: var(--color-{{ $category->color }}); 
                            background-color: {{ in_array($category->id, $categories) ? 'color-mix(in srgb, var(--color-' . $category->color . ') 15%, transparent)' : 'transparent'}};
                        "
                    >   
                        <span 
                            class="text-small text-center transition {{ count($categories) === 0 ? 'text-primary-text' : 'text-primary' }}"
                            style="color: var(--color-{{ $category->color }});"
                        >
                            {{ $category->name }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-background-secondary border border-border rounded-lg p-4 space-y-2">
            <label class="block text-small text-left text-primary-text">
                Status
            </label>
            <div class="w-full flex no-wrap gap-2 overflow-x-auto no-scrollbar">
                @foreach ($statuses as $statusItem)
                    <button
                        wire:click="$set('status', '{{ $statusItem['value'] }}')"
                        class="px-4 py-2 rounded-lg shrink-0 border border-primary transition {{ $status === $statusItem['value'] ? 'bg-primary' : 'bg-transparent' }}"
                    >   
                        <span 
                            class="text-small text-center transition {{ $status === $statusItem['value'] ? 'text-primary-text' : 'text-primary' }}"
                        >
                            {{ $statusItem['label'] }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Habits --}}
    <div class="space-y-3">
        @forelse ($this->habits() as $habit)
            @php
                $version = $habit->currentVersion;
                $category = $version?->category;
                $isEnded = $version?->ends_at && $version->ends_at->isPast();
                $isInactive = $habit->deleted_at !== null;
            @endphp
            <a @if(!$isInactive) href="{{ route('habits.show', $habit) }}" wire:navigate @endif class="block bg-background-secondary border border-border rounded-lg p-4 space-y-3">
                <header class="flex items-center gap-2 justify-between">
                    <span class="text-content text-primary-text text-left">
                        {{ $habit->name }}
                    </span>

                    <div
                        class="w-fit px-2 py-0.5 rounded-lg"
                        style="background-color: color-mix(in srgb, var(--color-{{ $category?->color }}) 15%, transparent);"
                    >
                        <span
                            class="text-sm text-left"
                            style="color: var(--color-{{ $category?->color }});"
                        >
                            {{ $category->name }}
                        </span>
                    </div>
                </header>

                <footer class="w-full pt-3 border-t border-border space-y-2">
                    <ul class="w-full flex flex-col gap-1">
                        <li class="flex justify-between gap-2">
                            <span class="block shrink-0 text-small text-secondary-text text-left">
                                Último realizado
                            </span>
                            <span class="block shrink-0 text-small text-secondary-text text-right">
                                {{ $this->lastCompletionLabel($habit) }}
                            </span>
                        </li>
                        <li class="flex justify-between gap-2">
                            <span class="block shrink-0 text-small text-secondary-text text-left">
                                Streak atual
                            </span>
                            <span class="block shrink-0 text-small text-secondary-text text-right">
                                --
                            </span>
                        </li>
                    </ul>

                    <div class="flex items-center gap-2">
                        <div 
                            class="
                                border-t flex-1 h-1
                                {{ $isInactive ? 'border-danger' : '' }}
                                {{ ! $isInactive && $isEnded ? 'border-alert' : '' }}
                                {{ ! $isInactive && ! $isEnded ? 'border-success' : '' }}
                            ">
                        </div>
                        <span 
                            class="
                                text-small text-center
                                {{ $isInactive ? 'text-danger' : '' }}
                                {{ ! $isInactive && $isEnded ? 'text-alert' : '' }}
                                {{ ! $isInactive && ! $isEnded ? 'text-success' : '' }}
                            "
                        >
                            Ativo
                        </span>
                        <div 
                            class="
                                border-t flex-1 h-1
                                {{ $isInactive ? 'border-danger' : '' }}
                                {{ ! $isInactive && $isEnded ? 'border-alert' : '' }}
                                {{ ! $isInactive && ! $isEnded ? 'border-success' : '' }}
                            ">
                        </div>
                    </div>
                </footer>
            </a>
        @empty
            <div class="bg-transparent border border-dashed border-border rounded-lg p-4">
                <span class="flex justify-center gap-1.5 text-content text-left text-primary-text">
                    <x-icon icon="circle-x-mark" class="w-6 h-6 object-scale-down" />
                    Nenhum hábito encontrado.
                </span>
            </div>
        @endforelse
    </div>
</div>