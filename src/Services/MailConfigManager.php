<?php

namespace Lwsoftbd\LaravelMailer\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Lwsoftbd\LaravelMailer\Models\SmtpSetting;

class MailConfigManager
{
    public static function apply(?string $queue = null): void
    {
        $queue = $queue ?? config('laravel-mailer.default_queue', 'default');

        $smtps = cache()->remember(
            "smtp_list_{$queue}",
            now()->addMinutes(config('laravel-mailer.cache_duration_minutes',60)),
            fn() => SmtpSetting::where('active',1)
                    ->where(fn($q) => $q->where('queue',$queue)->orWhereNull('queue'))
                    ->orderBy('priority')
                    ->get()
        );

        foreach ($smtps as $smtp) {
            try {
                Config::set('mail.default','smtp');
                Config::set('mail.mailers.smtp',[
                    'transport'=>'smtp',
                    'host'=>$smtp->host,
                    'port'=>$smtp->port,
                    'encryption'=>$smtp->encryption,
                    'username'=>$smtp->username,
                    'password'=>$smtp->password,
                ]);
                Config::set('mail.from',[
                    'address'=>$smtp->from_address,
                    'name'=>$smtp->from_name,
                ]);

                if(config('laravel-mailer.failover_enabled',true)){
                    Mail::raw('ping', fn() => null); // failover test
                }

                return;
            } catch (\Throwable $e) {
                continue; // next SMTP
            }
        }
    }
}
