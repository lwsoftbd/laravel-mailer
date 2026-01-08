<?php

namespace LWSoftBD\LaravelMailer\Helpers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use LWSoftBD\LaravelMailer\Models\Smtp;

class MailConfigHelper
{
    public static function load()
    {
        // ✅ Table না থাকলে কিছুই করবে না
        if (!Schema::hasTable('smtps')) {
            return;
        }

        $smtp = Smtp::where('is_default', true)->first();

        if (!$smtp) {
            return;
        }

        Config::set('mail.mailers.smtp', [
            'transport'  => 'smtp',
            'host'       => $smtp->host,
            'port'       => $smtp->port,
            'encryption' => $smtp->encryption,
            'username'   => $smtp->username,
            'password'   => $smtp->password,
        ]);

        Config::set('mail.from.address', $smtp->from_address);
        Config::set('mail.from.name', $smtp->from_name);
    }
}
