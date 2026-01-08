<?php

namespace LWSoftBD\LwMailer\Models;

use Illuminate\Database\Eloquent\Model;

class Smtp extends Model
{
    protected $fillable = [
        'mailer',
        'name',
        'hostDomain',
        'port',
        'from_name',
        'email_address',
        'password',
        'encryption',
        'api_key',
        'secret_key'
    ];

}