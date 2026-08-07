@props([
    'label',
    'name',
    'type' => 'text',
    'value' => null,
    'autocomplete' => null,
    'required' => false,
])
@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);
@endphp
<div {{ $attributes->only('class')->class(['space-y-1.5']) }}>
    <label for="{{ $id }}" class="block text-sm font-medium text-white/80">
        {{ $label }}
        @if ($required)
            <span class="text-xc-cyan">*</span>
        @endif
    </label>

    <input
        id="{{ $id }}"
        name="{{ $name }}"
        type="{{ $type }}"
        @if ($type !== 'password') value="{{ old($name, $value) }}" @endif
        @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if ($required) required @endif
        {{ $attributes->except('class')->class([
            'w-full rounded-lg border bg-xc-darker/80 px-3.5 py-2.5 text-sm text-white placeholder:text-white/35',
            'focus:outline-none focus:ring-2 focus:ring-xc-cyan/50 focus:border-xc-cyan/50',
            'border-red-500/50' => $hasError,
            'border-white/15' => ! $hasError,
        ]) }}
    >

    @error($name)
        <p class="text-xs text-red-300">{{ $message }}</p>
    @enderror
</div>
