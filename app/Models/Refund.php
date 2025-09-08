<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'buy_transaction_id',
        'reason',
        'status',
    ];

    public function buyTransaction()
    {
        return $this->belongsTo(BuyTransaction::class);
    }
}