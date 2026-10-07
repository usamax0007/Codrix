@php
    $class = $class ?? 'xc-btn-primary';
    $label = $label ?? 'Book a Call';
    $bookingUrl = config('site.booking_url');
    $href = $bookingUrl ?: url('/contact');
@endphp
<a href="{{ $href }}" class="{{ $class }}" @if($bookingUrl) target="_blank" rel="noopener" @endif>{{ $label }}</a>
