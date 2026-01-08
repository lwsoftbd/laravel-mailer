<?php

namespace LWSoftBD\LwMailer\Database\Seeders;

use LWSoftBD\LwMailer\Models\Smtp;
use LWSoftBD\LwMailer\Mailer;
use Illuminate\Database\Seeder;

class SmtpSeeder extends Seeder
{
    public function run()
    {
        Smtp::create([
            'mailer'       => 'smtp',
            'hostDomain'   => '127.0.0.1',
            'port'         => '1025',
            'email_address' => 'noreply@example.com',
            'password'     => null,
            'encryption'   => null,
            'api_key'      => null,
            'secret_key'   => null,
            'created_at'   => now()
        ]);
    }
}