<?php

use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component
{
    public array $navItems = [
        ['label' => 'Início', 'route' => 'home', 'icon'  => 'house'],
        ['label' => 'Hábitos', 'route' => 'habits.index', 'icon'  => 'clock-check'],
        ['label' => 'Trilhas', 'route' => 'learning-trails.index', 'icon'  => 'route'],
        ['label' => 'Perfil', 'route' => 'user.show', 'icon'  => 'user-round'],
    ];

    #[Computed]
    public function items(): array
    {
        $currentRoute = request()->route()?->getName();
 
        return collect($this->navItems)
            ->map(fn ($item) => [
                ...$item,
                'active' => $currentRoute === $item['route']
                         || str_starts_with((string) $currentRoute, explode('.', $item['route'])[0]),
                'url'    => route($item['route']),
            ])
            ->all();
    }
};
?>

<div class="w-full flex items-center justify-between px-8 py-2.5">
    @foreach ($this->items as $item)
        <a
            href="{{ $item['url'] }}"
            wire:navigate.hover
            class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 {{ $item['active'] ? 'bg-primary/15' : 'bg-transparent' }}"
        >

            <x-icon :icon="$item['icon']" class="w-5 h-5 object-scale-down {{ $item['active'] ? 'text-primary' : 'text-secondary-text' }}" />
 
            @if ($item['active'])
                <span class="text-smallt text-primary whitespace-nowrap animate-[fade-in_.25s_ease-out]">
                    {{ $item['label'] }}
                </span>
            @endif
        </a>
    @endforeach
</div>