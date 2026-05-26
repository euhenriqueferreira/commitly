@props([
    'title',
    'back' => false,
])


<header class="absolute top-3 left-3 right-3 z-20 flex items-center justify-between gap-3 h-15 bg-background-secondary border border-primary/50 rounded-xl px-4 py-3">
    @if($back)
        <x-actions.nav-action :href="$back">
            <x-icon icon="arrow-left" class="w-5 h-5 text-secondary-text object-scale-down" />
        </x-actions.nav-action>
    @endif
    
    <h1 class="flex-1 text-heading-1 text-primary-text text-left truncate">
        {{ $title }}
    </h1>

    <div class="flex gap-1">
        {{ $slot }}
    </div>
</header>