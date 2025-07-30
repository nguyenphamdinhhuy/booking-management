<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'notification_email',
        'notification_sms',
        'language',
        'timezone',
        'status',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notification_email' => 'boolean',
            'notification_sms' => 'boolean',
        ];
    }

    // Accessor cho avatar
    public function getAvatarAttribute($value)
    {
        if ($value) {
            return $value;
        }
        return asset('images/default-avatar.png');
    }

    // Mutator cho language
    public function setLanguageAttribute($value)
    {
        $this->attributes['language'] = $value ?: 'vi';
    }

    // Mutator cho timezone
    public function setTimezoneAttribute($value)
    {
        $this->attributes['timezone'] = $value ?: 'Asia/Ho_Chi_Minh';
    }
}
