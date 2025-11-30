<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'year_of_birth',
        'zip_code',
        'is_decision_maker',
        'has_medicare_part_b',
        'google_id',
        'auth_provider',
        'is_guest',
        'is_admin',
        'email_verified_at',
        'email_verification_code',
        'email_verification_expires_at',
        'password_reset_code',
        'password_reset_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_code',
        'email_verification_expires_at',
        'password_reset_code',
        'password_reset_expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'email_verification_expires_at' => 'datetime',
            'password_reset_expires_at' => 'datetime',
            'password' => 'hashed',
            'is_decision_maker' => 'boolean',
            'has_medicare_part_b' => 'boolean',
            'is_guest' => 'boolean',
            'is_admin' => 'boolean',
            'year_of_birth' => 'integer',
        ];
    }

    /**
     * Get the full name attribute.
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the callback requests for the user.
     */
    public function callbackRequests()
    {
        return $this->hasMany(\App\Models\CallbackRequest::class);
    }

    /**
     * Get the activity logs for the user.
     */
    public function activityLogs()
    {
        return $this->hasMany(\App\Models\ActivityLog::class);
    }
}
