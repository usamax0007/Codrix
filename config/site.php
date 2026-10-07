<?php

return [
    'booking_url' => env('BOOKING_URL', ''),
    
    'whatsapp' => [
        'number' => env('WHATSAPP_NUMBER', ''),
        'enabled' => env('WHATSAPP_NUMBER', '') !== '',
    ],
    
    'upwork' => [
        'profile_url' => env('UPWORK_PROFILE_URL', ''),
        'show_badge' => env('UPWORK_SHOW_BADGE', false),
    ],
    
    'analytics' => [
        'provider' => env('ANALYTICS_PROVIDER', ''),
        'id' => env('ANALYTICS_ID', ''),
        'enabled' => env('ANALYTICS_PROVIDER', '') !== '' && env('ANALYTICS_ID', '') !== '',
    ],
    
    'tools' => [
        ['name' => 'Twilio', 'url' => 'https://twilio.com'],
        ['name' => 'SignalWire', 'url' => 'https://signalwire.com'],
        ['name' => 'LiveKit', 'url' => 'https://livekit.io'],
        ['name' => 'Retell', 'url' => 'https://retellai.com'],
        ['name' => 'Vapi', 'url' => 'https://vapi.ai'],
        ['name' => 'ElevenLabs', 'url' => 'https://elevenlabs.io'],
        ['name' => 'OpenAI', 'url' => 'https://openai.com'],
        ['name' => 'Laravel', 'url' => 'https://laravel.com'],
        ['name' => 'Vue', 'url' => 'https://vuejs.org'],
    ],
];
