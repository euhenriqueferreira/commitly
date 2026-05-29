@props([
    'wireModel',
    'label',
    'wireType' => 'defer',
    'placeholder' => null,
    'value' => null,
    'isRequired' => false,
])

<div class="flex w-full flex-col-reverse items-start gap-1">
    @error($wireModel)
        <footer class="flex w-full items-center justify-between gap-3 pl-0.5">
            <span class="text-danger font-text text-left text-xs leading-4 font-normal">{{ $message }}</span>
        </footer>
    @enderror

    <div class="flex w-full gap-1.5">
        <input 
            type="time" 
            id="{{ $wireModel }}" 
            @if($wireType === 'live') ? wire:model.live="{{ $wireModel }}" @else wire:model.defer="{{ $wireModel }}" @endif
            placeholder="{{ $placeholder }}" 
            value="{{ $value }}" 
            {{ $attributes->merge(['class' => 'peer w-full border border-border text-small text-primary-text placeholder:text-secondary-text text-left focus:shadow-sm focus:shadow-primary/30 h-11 rounded-md px-4 focus:outline-none']) }} />
    </div>

    @if (isset($label))
        <label for="{{ $wireModel }}" class="text-small text-left text-primary-text">
            {{ $label }}
            @if ($isRequired)
                <span class="text-danger">*</span>
            @else
                <span class="text-secondary-text">(?)</span>
            @endif
        </label>
    @endif
</div>
