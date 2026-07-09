@php
    $domain = config('xcodrix.domain');
    $org = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => $domain . '/#organization',
        'name' => 'XCodrix',
        'url' => $domain,
        'logo' => asset('images/xcodrix-logo.png'),
        'description' => 'XCodrix is a software development agency specializing in AI, SaaS, Laravel, Vue.js, mobile apps, and Twilio communication systems.',
        'email' => config('xcodrix.email'),
        'sameAs' => array_values(config('xcodrix.social')),
    ];
@endphp
<script type="application/ld+json">{!! json_encode($org, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
