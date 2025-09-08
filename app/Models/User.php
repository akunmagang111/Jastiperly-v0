<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'account_status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi
     */
    public function detail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'submiter_id');
    }

    public function buyTransactions()
    {
        return $this->hasMany(BuyTransaction::class, 'buyer_id');
    }

    public function travelTransactions()
    {
        return $this->hasMany(BuyTransaction::class, 'traveler_id');
    }

    public function sendTransactions()
    {
        return $this->hasMany(SendTransaction::class, 'sender_id');
    }
}