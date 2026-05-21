@props([
    'icon',
])

<x-dynamic-component :component="'icons.' . $icon" {{ $attributes }} />
