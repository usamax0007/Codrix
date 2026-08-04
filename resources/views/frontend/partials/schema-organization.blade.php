@php
    $domain = config('xcodrix.domain');
    $org = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => $domain . '/#organization',
        'name' => $siteSettings->site_name,
        'url' => $domain,
        'logo' => $siteSettings->logoUrl(),
        'description' => $siteSettings->short_description
            ?: 'XCodrix is a software development agency specializing in AI, SaaS, Laravel, Vue.js, mobile apps, and Twilio communication systems.',
        'email' => $siteSettings->email,
        'telephone' => $siteSettings->phone,
        'sameAs' => array_values($siteSettings->socialLinks()),
    ];

    if ($siteSettings->address) {
        $org['address'] = [
            '@type' => 'PostalAddress',
            'streetAddress' => $siteSettings->address,
        ];
    }

    $org = array_filter($org, fn ($value) => $value !== null && $value !== []);
@endphp
<script type="application/ld+json">{!! json_encode($org, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
