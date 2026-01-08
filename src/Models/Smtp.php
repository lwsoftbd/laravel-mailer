<?php

namespace LWSoftBD\LaravelMailer\Models;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;

class Smtp extends Model
{
    protected $fillable = [
        'mailer',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address',
        'from_name',
        'is_default'
    ];

    protected static function booted()
    {
        static::saving(function ($smtp) {
            if ($smtp->is_default) {
                self::where('id', '!=', $smtp->id)
                    ->update(['is_default' => false]);
            }
        });
    }

    public function setPasswordAttribute($value)
    {
        if (config('laravel-mailer.smtp.encrypt_password') && $value) {
            $this->attributes['password'] = Crypt::encryptString($value);
        }
    }

    public function getPasswordAttribute($value)
    {
        if (config('laravel-mailer.smtp.encrypt_password') && $value) {
            return Crypt::decryptString($value);
        }

        return $value;
    }
}
