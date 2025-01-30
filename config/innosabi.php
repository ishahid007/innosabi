<?php

/*
|--------------------------------------------------------------------------
| Innosabi API Configuration
|--------------------------------------------------------------------------
|
| Here you may specify the configuration for the Innosabi API.
|
*/

return [
    'api' => [
        'base_url' => env('INNOSABI_API_URL'),
        'username' => env('INNOSABI_API_USERNAME'),
        'password' => env('INNOSABI_API_PASSWORD'),
    ],
];
