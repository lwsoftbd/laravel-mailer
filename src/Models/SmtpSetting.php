<?php

namespace Lwsoftbd\LaravelMailer\Models;

use Illuminate\Database\Eloquent\Model;

class SmtpSetting extends Model
{
    protected $table = 'laravel_mailer_smtp_settings';
    protected $fillable = [
        'host','port','username','password','encryption',
        'from_address','from_name','queue','priority','active'
    ];
}
