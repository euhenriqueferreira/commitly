@props([
    'href' => false,
    'size' => 'full',
    'disabled' => false,
])

@php
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }} 
    @if($href) href="{{ $href }}" @endif 
    {{ $attributes->merge(['class' => ($size == 'full' ? 'w-full' : 'w-fit') . ($disabled ? ' opacity-65' : ' opacity-100 hover:brightness-95 cursor-pointer') . ' bg-primary text-small text-white h-13 rounded-lg px-4 transition']) }} 
    @if($disabled) disabled @endif
    >
    {{ $slot }}
</{{ $tag }}>