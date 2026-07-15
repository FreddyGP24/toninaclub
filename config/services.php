<?php

return [
    'postmark' => ['token' => env('POSTMARK_TOKEN')],
    'resend' => ['key' => env('RESEND_KEY')],
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'google_maps' => [
        'browser_key' => env('GOOGLE_MAPS_BROWSER_KEY'),
        'server_key' => env('GOOGLE_MAPS_SERVER_KEY'),
        'map_id' => env('GOOGLE_MAPS_MAP_ID', 'DEMO_MAP_ID'),
    ],
];
