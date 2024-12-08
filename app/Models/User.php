<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    const NAME = 'name';
    const EMAIL = 'email';
    const PASSWORD = 'password';
    const MICROSOFT_ID = 'microsoft_id';
    const ACCESS_TOKEN = 'access_token';
    const REFRESH_TOKEN = 'refresh_token';
    const REMEMBER_TOKEN = 'remember_token';
    const TOKEN_EXPIRES_IN = 'token_expires_in';
    const EMAIL_VERIFIED_AT = 'email_verified_at';
    
    const TABLE_NAME = 'users';
    
    const ENTITY_NAME = 'user';
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        self::NAME,
        self::EMAIL,
        self::PASSWORD,
        self::MICROSOFT_ID,
        self::ACCESS_TOKEN,
        self::REFRESH_TOKEN,
        self::TOKEN_EXPIRES_IN,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        self::PASSWORD,
        self::ACCESS_TOKEN,
        self::REFRESH_TOKEN,
        self::REMEMBER_TOKEN,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            self::PASSWORD => 'hashed',
            self::EMAIL_VERIFIED_AT => 'datetime',
        ];
    }
}
