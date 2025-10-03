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
        'status',
        'email_verified_at',
        'google_id',
        'remember_token',
        'is_verification_enabled',
        'phone',
        'address',
        'gender',
        'dob',
        'start_date',
        'citizen_id',
        'issue_date',
        'issue_place',
        'id_card_front',
        'id_card_back',
        'contract_type',
        'salary',
        'notification_email',
        'notification_sms',
        'language',
        'timezone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'dob' => 'date',
            'start_date' => 'date',
            'issue_date' => 'date',
            'is_verification_enabled' => 'boolean',
            'notification_email' => 'boolean',
            'notification_sms' => 'boolean',
            'password' => 'hashed',
        ];
    }

    // Accessor cho avatar
    public function getAvatarAttribute($value)
    {
        return $value ?: asset('images/default-avatar.png');
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