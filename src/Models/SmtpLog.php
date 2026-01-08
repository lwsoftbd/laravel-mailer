<?php

namespace LWSoftBD\LwMailer\Models;

use Illuminate\Database\Eloquent\Model;

class SmtpLog extends Model
{
    protected $fillable = [
        'from_name',
        'from_email',
        'to_email',
        'message',
        'status',
        'error_message',
        'created_at',
        'updated_at'
    ];
}
