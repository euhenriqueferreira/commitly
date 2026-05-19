@props([
    'type' => 'alert', // info, alert, danger, success
])

@php
    $color =  match($type) {
        "info" => 'bg-primary/15 border-primary text-primary',
        "alert" => 'bg-alert/15 border-alert text-alert',
        "danger" => 'bg-danger/15 border-danger text-danger',
        "success" => 'bg-success/15 border-success text-success',
    };
       
@endphp

<div class="w-full rounded-md p-4 border text-small {{ $color }}">
    {{ $slot }}
</div>
{{-- 
<{{ $tag }} 
    @if($href) href="{{ $href }}" @endif 
    {{ $attributes->merge(['class' => ($size == 'full' ? 'w-full' : 'w-fit') . ($disabled ? ' opacity-65' : ' opacity-100 hover:brightness-95 cursor-pointer') . ' bg-primary text-small text-white h-13 rounded-lg px-4 transition']) }} 
    @if($disabled) disabled @endif
    >
    {{ $slot }}
</{{ $tag }}> --}}