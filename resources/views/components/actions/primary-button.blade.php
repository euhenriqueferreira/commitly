@props([
    'href' => false,
    'size' => 'full',
    'disabled' => false,
    'loading' => false,
])

@php
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }} 
    @if($href) href="{{ $href }}" @endif 
    {{ $attributes->merge(['class' => ($size == 'full' ? 'w-full' : 'w-fit') . ($disabled ? ' opacity-65' : ' opacity-100 hover:brightness-95 cursor-pointer') . ' bg-primary flex items-center justify-center h-13 rounded-lg px-4 transition']) }} 
    @if($disabled) disabled @endif
    >
    @if($loading)
        <div wire:loading wire:target="{{ $loading }}">
            <div class="flex flex-row gap-1">
                <div class="w-1.5 h-1.5 rounded-full bg-white animate-loading-dot"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-white animate-loading-dot [animation-delay:0.2s]"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-white animate-loading-dot [animation-delay:0.4s]"></div>
            </div>
        </div>
    @endif
    
    <span class="flex items-center justify-center gap-2 text-small text-center text-white" @if($loading) wire:loading.remove wire:target="{{ $loading }}" @endif>
        {{ $slot }}
    </span>
</{{ $tag }}>