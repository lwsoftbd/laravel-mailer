<?php

return [
    'default_queue' => 'default',
    'cache_duration_minutes' => 60,
    'failover_enabled' => true,
    'auto_listener' => true,
    'admin' => [
        'route_prefix' => 'admin/laravel-mailer',
        'middleware' => ['web','auth'],
    ],
];
