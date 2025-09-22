<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'type',
        'name',
        'account_name',
        'account_number',
    ];

    public function buyTransactions()
    {
        return $this->hasMany(BuyTransaction::class);
    }

    public function sendTransactions()
    {
        return $this->hasMany(SendTransaction::class);
    }
}