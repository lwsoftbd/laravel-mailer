<?php

namespace LWSoftBD\LwMailer\Models;

use Illuminate\Database\Eloquent\Model;

class Mailer extends Model
{
    protected $fillable = [
        'name', 'mailer', 'encryption', 'hostDomain', 'port',
        'from_name', 'email_address', 'password', 'api_key',
        'secret_key', 'is_default', 'note',
    ];

}