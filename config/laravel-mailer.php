<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Package Enable
    |--------------------------------------------------------------------------
    | false করলে package এর route, UI, SMTP load হবে না
    |
    */
    'enabled' => env('LARAVEL_MAILER_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    */
    'route' => [
        // URL: /mail-smtp
        'prefix' => env('LARAVEL_MAILER_ROUTE_PREFIX', 'mail-smtp'),

        // Route name: mailer.*
        'name' => 'mailer.',

        // Middleware (default auth)
        'middleware' => ['web', 'auth'],
    ],

    /*
    |--------------------------------------------------------------------------
    | SMTP Behavior
    |--------------------------------------------------------------------------
    */
    'smtp' => [

        // Default mailer name
        'mailer' => 'smtp',

        // Password encryption enable
        'encrypt_password' => true,

        // Auto reload mail config when default SMTP changes
        'auto_reload' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | UI Options
    |--------------------------------------------------------------------------
    */
    'ui' => [

        // Use package views or allow override
        'use_package_views' => true,
    ],

];
