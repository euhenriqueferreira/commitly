<?php

use Livewire\Component;
use App\Services\Habits\CreateHabitService;
use App\Models\Category;
use App\Enum\Habits\WeekdayEnum;
use Illuminate\Validation\Rule;


new class extends Component
{
    public string $name = '';
    public ?int $category = null;
    public array $weekdays = [];
    public ?string $reminderTime = null;
    public ?string $endsAt = null;

    public function save(CreateHabitService $service): void {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'exists:categories,id'],
            'weekdays' => ['required', 'array', 'min:1'],
            'weekdays.*' => ['integer', Rule::in(WeekdayEnum::values())],
            'reminderTime' => ['nullable', 'date_format:H:i:s'],
            'endsAt' => ['nullable', 'date', 'after_or_equal:today'],
        ]);
        

        $service->handle([
            'name' => $validated['name'],
            'category_id' => $validated['category'],
            'weekdays' => $validated['weekdays'],
            'reminder_time' => $validated['reminderTime'],
            'ends_at' => $validated['endsAt'],
        ]);

        $this->redirectRoute('home');
    }

    public function toggleWeekday(int $weekday): void
    {
        if (in_array($weekday, $this->weekdays)) {
            $this->weekdays = array_values(array_filter($this->weekdays, fn ($day) => $day !== $weekday));
            return;
        }

        $this->weekdays[] = $weekday;
        sort($this->weekdays);
    }

    public function selectCategory(int $category): void
    {
        $this->category = $category;
    }

    public function categories()
    {
        return Category::query()->get();
    }
};
?>

<div class="contents">
    <x-structure.page-header title="Novo hábito" />

    <form class="space-y-3" wire:submit="save">
        <div class="bg-background-secondary border border-border rounded-lg p-4 space-y-2">
            <x-form.input-text wireModel="name" label="Nome do hábito" placeholder="ex: Meditar, treinar, ler..." isRequired />
        </div>
        
        <div class="bg-background-secondary border border-border rounded-lg p-4 space-y-2">
            <label class="block text-small text-left text-primary-text">
                Categoria
                <span class="text-danger">*</span>
            </label>

            <div class="grid grid-cols-2 gap-2">
                @foreach ($this->categories() as $categoryItem)
                    <button 
                        type="button" 
                        wire:click="selectCategory({{ $categoryItem->id }})"
                        class="bg-transparent p-3 border rounded-xl flex flex-col items-center gap-2 transition"
                        style="
                            border-color: var(--color-{{ $categoryItem->color }});
                            background-color: {{ $category === $categoryItem->id ? 'color-mix(in srgb, var(--color-' . $categoryItem->color . ') 15%, transparent)' : 'transparent' }};
                        "
                    >
                        <x-icon 
                            icon="{{ $categoryItem->icon }}" 
                            class="w-6 h-6 object-scale-down" 
                            style="color: var(--color-{{ $categoryItem->color }});" 
                        />

                        <span class="text-small" style="color: var(--color-{{ $categoryItem->color }});">
                            {{ $categoryItem->name }}
                        </span>
                    </button>
                @endforeach
            </div>

            @error('category')
                <span class="text-danger font-text text-left text-xs leading-4 font-normal">{{ $message }}</span>
            @enderror
        </div>

        <div class="bg-background-secondary border border-border rounded-lg p-4 space-y-2">
            <label class="block text-small text-left text-primary-text">
                Dias da semana
                <span class="text-danger">*</span>
            </label>

            <div class="grid grid-cols-4 gap-2">
                @foreach (WeekdayEnum::cases() as $weekday)
                    <button 
                        type="button" 
                        wire:click="toggleWeekday({{ $weekday->value }})"
                        class="py-2 px-6 {{ in_array($weekday->value, $weekdays) ? 'bg-primary/15 border-primary' : 'bg-transparent border-border'}} border rounded-lg flex flex-col items-center gap-2 transition"
                    >
                        <span class="text-small {{ in_array($weekday->value, $weekdays) ? 'text-primary' : 'text-primary-text'}}">
                            {{ $weekday->shortLabel() }}
                        </span>
                    </button>
                @endforeach
            </div>

            @error('weekdays')
                <span class="text-danger font-text text-left text-xs leading-4 font-normal">{{ $message }}</span>
            @enderror
        </div>

        <div class="bg-background-secondary border border-border rounded-lg p-4 space-y-2">
            <x-form.input-time wireModel="reminderTime" label="Horário de lembrete" />
            <x-form.input-date wireModel="endsAt" label="Data limite" />
        </div>

        <x-actions.primary-button loading="save">Criar hábito</x-actions.primary-button>
    </form>
</div>