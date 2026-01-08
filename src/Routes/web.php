<?php

use Illuminate\Support\Facades\Route;
use Lwsoftbd\LaravelMailer\Http\Controllers\SmtpController;

Route::prefix(config('laravel-mailer.admin.route_prefix','admin/laravel-mailer'))
    ->middleware(config('laravel-mailer.admin.middleware', ['web','auth']))
    ->group(function(){
        Route::get('/', [SmtpController::class,'index'])->name('laravel-mailer.index');
        Route::post('/save', [SmtpController::class,'store'])->name('laravel-mailer.save');
        Route::post('/toggle/{id}', [SmtpController::class,'toggle'])->name('laravel-mailer.toggle');
    });
