<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'document',
        'type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function isMerchant(): bool
    {
        return $this->type === 'merchant';
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}