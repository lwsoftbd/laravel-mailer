<?php

namespace LWSoftBD\LaravelMailer;

use Illuminate\Support\ServiceProvider;
use LWSoftBD\LaravelMailer\Helpers\MailConfigHelper;

class LaravelMailerServiceProvider extends ServiceProvider
{
    public function register()
    {
        // ✅ config আগে merge হবে
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-mailer.php',
            'laravel-mailer'
        );
    }

    public function boot()
    {
        // routes only if enabled
        if (config('laravel-mailer.enabled')) {
            $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        }

        $this->loadViewsFrom(__DIR__.'/resources/views', 'laravel-mailer');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // config publish
        $this->publishes([
            __DIR__.'/../config/laravel-mailer.php' => config_path('laravel-mailer.php'),
        ], 'laravel-mailer-config');

        // ✅ Runtime-safe load (after app booted)
        app()->booted(function () {
            MailConfigHelper::load();
        });
    }
}
