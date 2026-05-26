@props([
    'href' => false,
])

@php
    $tag = $href ? 'a' : 'button';

    $baseClasses = 'flex items-center justify-center p-1.5 rounded-lg border border-secondary-text';
@endphp

<{{ $tag }}
    {{ $attributes->merge([
        'class' => $baseClasses,
        'href' => $href,
    ]) }}
>
        {{ $slot }}
</{{ $tag }}>