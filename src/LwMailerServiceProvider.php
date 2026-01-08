<?php

namespace LWSoftBD\LwMailer;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class LwMailerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // load route
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        // load view
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'lw-mailer');

        // load migration
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Publish config only
        $this->publishes([
            __DIR__ . '/../config/lw-mailer.php' => config_path('lw-mailer.php'),
        ], ['lw-mailer-config', 'lw-mailer-all']);

        // Publish view only
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/lw-mailer/mailer'),
        ], ['lw-mailer-views', 'lw-mailer-all']);

        // Publish seeder (optional)
        $this->publishes([
            __DIR__ . '/../database/seeders/SmtpSeeder.php' => database_path('seeders/SmtpSeeder.php'),
        ], ['lw-mailer-seeder', 'lw-mailer-all']);

        View::share('documentation', 'http://lwsoftbd.com/lw-mailer');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/lw-mailer.php',
            'lw-mailer'
        );
    }
}