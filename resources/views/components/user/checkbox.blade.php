@props([
    'name',
    'label',
    'value' => '1',
])
<label {{ $attributes->class(['inline-flex items-center gap-2 text-sm text-white/70 cursor-pointer select-none']) }}>
    <input
        type="checkbox"
        name="{{ $name }}"
        value="{{ $value }}"
        @checked(old($name, false))
        class="rounded border-white/20 bg-xc-darker text-xc-cyan focus:ring-xc-cyan/40"
    >
    <span>{{ $label }}</span>
</label>
