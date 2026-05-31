<?php

return [
    'default' => env('APP_LOCALE', 'en'),
    'fallback' => env('APP_FALLBACK_LOCALE', 'en'),

    'supported' => [
        'en' => [
            'name' => 'English',
            'label_key' => 'common.languages.english',
        ],
        'es' => [
            'name' => 'Spanish',
            'label_key' => 'common.languages.spanish',
        ],
    ],
];
