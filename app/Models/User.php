<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'account_status', // tambahan dari ERD
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
        ];
    }

    /**
     * ====================
     * Relasi
     * ====================
     */

    // Detail user
    public function detail()
    {
        return $this->hasOne(UserDetail::class);
    }

    // Produk yang disubmit user
    public function products()
    {
        return $this->hasMany(Product::class, 'submiter_id');
    }

    // Transaksi pembelian (sebagai pembeli)
    public function buyTransactions()
    {
        return $this->hasMany(BuyTransaction::class, 'buyer_id');
    }

    // Transaksi perjalanan (sebagai traveler)
    public function travelTransactions()
    {
        return $this->hasMany(BuyTransaction::class, 'traveler_id');
    }

    // Transaksi pengiriman (sebagai pengirim)
    public function sendTransactions()
    {
        return $this->hasMany(SendTransaction::class, 'sender_id');
    }
}
