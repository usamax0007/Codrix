@php
    $class = $class ?? 'xc-btn-primary';
    $label = $label ?? 'Book a Call';
    $tell = $siteSettings->phone;
@endphp
<a href="tel:{{ $tell }}" class="{{ $class }}">{{ $label }}</a>
