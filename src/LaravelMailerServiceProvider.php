<?php

namespace LWSoftBD\LaravelMailer;

use Illuminate\Support\ServiceProvider;
use LWSoftBD\LaravelMailer\Helpers\MailConfigHelper;

class LaravelMailerServiceProvider extends ServiceProvider
{

    public function boot()
    {
        if (config('laravel-mailer.enabled')) {
            $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        }

        $this->loadViewsFrom(__DIR__.'/resources/views', 'laravel-mailer');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    

        $this->publishes([
            __DIR__.'/../config/laravel-mailer.php' => config_path('laravel-mailer.php'),
        ], 'laravel-mailer-config');

        MailConfigHelper::load();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-mailer.php',
            'laravel-mailer'
        );
    }
}
