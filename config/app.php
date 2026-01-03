<?php

return [
    'name' => env('APP_NAME', 'SchoolDream+'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost:3000'),
    'timezone' => 'Africa/Kigali',
    
    'session' => [
        'lifetime' => env('SESSION_LIFETIME', 120),
        'cookie_name' => env('SESSION_COOKIE_NAME', 'schooldream_session'),
    ],
    
    'jwt' => [
        'secret' => env('JWT_SECRET', 'change-this-secret'),
        'algorithm' => 'HS256',
        'expiration' => 3600,
    ],
    
    'upload' => [
        'max_size' => env('MAX_FILE_SIZE', 10485760),
        'allowed_types' => explode(',', env('ALLOWED_FILE_TYPES', 'pdf,doc,docx,mp4,jpg,jpeg,png')),
    ],
    
    'pagination' => [
        'per_page' => env('ITEMS_PER_PAGE', 12),
    ],
];
