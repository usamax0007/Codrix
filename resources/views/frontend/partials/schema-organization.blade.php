@php
    $domain = config('app.url');
    $orgSchema = [
        '@' . 'context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => $domain . '/#organization',
        'name' => 'Xcodrix',
        'url' => $domain,
        'logo' => asset('images/xcodrix-logo-dark.svg'),
        'description' => 'Software studio in Multan, Pakistan building AI voice agents, VoIP systems, and WhatsApp automation for startups and SMBs.',
        'email' => config('xcodrix.email'),
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => 'Multan',
            'addressRegion' => 'Punjab',
            'addressCountry' => 'PK',
        ],
        'sameAs' => [
            config('xcodrix.social.linkedin'),
            config('xcodrix.social.linkedin_founder'),
        ],
    ];
    
    $professionalSchema = [
        '@' . 'context' => 'https://schema.org',
        '@type' => 'ProfessionalService',
        'name' => 'Xcodrix',
        'url' => $domain,
        'founder' => [
            '@type' => 'Person',
            'name' => 'Usama Tahir',
            'sameAs' => config('xcodrix.social.linkedin_founder'),
        ],
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => 'Multan',
            'addressRegion' => 'Punjab',
            'addressCountry' => 'PK',
        ],
        'areaServed' => ['US', 'GB', 'CA', 'AE', 'SA'],
        'serviceType' => ['AI Voice Agents', 'VoIP Development', 'Call Center Systems', 'WhatsApp Automation'],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
<script type="application/ld+json">{!! json_encode($professionalSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
