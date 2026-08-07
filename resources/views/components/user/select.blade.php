@props([
    'label' => null,
    'name' => 'range',
    'options' => [],
    'value' => null,
])
@php
    $id = $attributes->get('id', $name);
@endphp
<div {{ $attributes->only('class')->class(['flex items-center gap-2']) }}>
    @if ($label)
        <label for="{{ $id }}" class="text-sm text-white/50 whitespace-nowrap">{{ $label }}</label>
    @endif

    <select
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $attributes->except('class')->class([
            'rounded-lg border border-white/15 bg-xc-darker/80 px-3 py-2 text-sm text-white',
            'focus:outline-none focus:ring-2 focus:ring-xc-cyan/50 focus:border-xc-cyan/50',
        ]) }}
    >
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
</div>
