@props([
    'href' => false,
    'size' => 'full',
])

@php
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }} 
    @if($href) href="{{ $href }}" @endif 
    {{ $attributes->merge(['class' => ($size == 'full' ? 'w-full' : 'w-fit') .  ' bg-primary/15 text-small text-primary h-10 rounded-lg px-4 transition']) }} 
    >
    {{ $slot }}
</{{ $tag }}>