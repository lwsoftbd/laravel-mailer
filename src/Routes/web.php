<?php

use Illuminate\Support\Facades\Route;
use LWSoftBD\LaravelMailer\Http\Controllers\SmtpController;

$route = config('laravel-mailer.route');

Route::middleware($route['middleware'])
    ->prefix($route['prefix'])
    ->name($route['name'])
    ->group(function () {

        Route::get('/', [SmtpController::class, 'index'])->name('index');
        Route::get('/create', [SmtpController::class, 'create'])->name('create');
        Route::post('/store', [SmtpController::class, 'store'])->name('store');

        Route::get('/{smtp}/edit', [SmtpController::class, 'edit'])->name('edit');
        Route::put('/{smtp}', [SmtpController::class, 'update'])->name('update');
        Route::delete('/{smtp}', [SmtpController::class, 'delete'])->name('delete');

        Route::post('/{smtp}/default', [SmtpController::class, 'makeDefault'])->name('default');
        Route::post('/test', [SmtpController::class, 'test'])->name('test');
    });
