<?php

use Illuminate\Support\Facades\Route;
use LWSoftBD\LwMailer\Http\Controllers\MailerController;
use LWSoftBD\LwMailer\Http\Controllers\SmtpController;

// SMTP/Mailer Routes
Route::middleware(['web', 'auth'])->prefix('smtp')->group(function () {
    //Mailer
    Route::get('mailer', [MailerController::class, 'index'])->name('mailer.index');
    Route::get('new-mailer', [MailerController::class, 'create'])->name('mailer.create');
    Route::post('mailers/store', [MailerController::class, 'store'])->name('mailer.store');
    Route::get('mailer/{id}', [MailerController::class, 'edit'])->name('mailer.edit');
    Route::put('{id}', [MailerController::class, 'update'])->name('mailer.update');
    // Update default smtp mailer
    Route::patch('default', [MailerController::class, 'updateDefault'])->name('mailers.updateDefault');
    // Mark default
    Route::patch('{id}/default', [MailerController::class, 'markDefault'])->name('mailer.markDefault');
    // Default mailer
    Route::get('mailer-default', [MailerController::class, 'defaultMailer'])->name('mailers.default');

    //SMTP
    Route::get('/', [SmtpController::class, 'index'])->name('settings.smtp');
    Route::post('/', [SmtpController::class, 'update'])->name('settings.smtp.update');
    Route::post('send', [SmtpController::class, 'sendMailTest'])->name('settings.send.email');
    // Retry email
    Route::get('logs/retry/{id}', [SmtpController::class, 'reTry'])->name('smtp.logs.retry');
    // Resend email
    Route::get('logs/resend/{id}', [SmtpController::class, 'resend'])->name('smtp.logs.resend');
    // Show logs
    Route::get('logs', [SmtpController::class, 'log'])->name('smtp.logs');
    // Delete logs
    Route::delete('logs/{id}', [SmtpController::class, 'destroy'])->name('smtp.logs.destroy');
});