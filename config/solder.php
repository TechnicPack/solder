<?php

return [

    'services' => [

        'platform' => [
            'key' => env('TECHNIC_PLATFORM_KEY'),
        ],

    ],

    'modpacks' => [
        'icon' => [
            'max_filesize' => 10000,
        ],
        'logo' => [
            'max_filesize' => 50000,
        ],
        'background' => [
            'max_filesize' => 1e+6,
        ],
    ],

];
