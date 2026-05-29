@props([
    'href' => false,
    'size' => 'full',
])

@php
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }} 
    @if($href) href="{{ $href }}" @endif 
    {{ $attributes->merge(['class' => ($size == 'full' ? 'w-full' : 'w-fit') .  ' bg-danger/15 text-small text-danger h-10 rounded-lg px-4 transition']) }} 
    >
    {{ $slot }}
</{{ $tag }}>