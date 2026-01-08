<?php

namespace Lwsoftbd\LaravelMailer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Mail\Events\MessageSending;
use Lwsoftbd\LaravelMailer\Listeners\ResolveSmtpBeforeSend;

class LaravelMailerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-mailer.php',
            'laravel-mailer'
        );
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/Http/Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views','laravel-mailer');

        if(config('laravel-mailer.auto_listener',true)){
            Event::listen(
                MessageSending::class,
                ResolveSmtpBeforeSend::class
            );
        }
    }
}
