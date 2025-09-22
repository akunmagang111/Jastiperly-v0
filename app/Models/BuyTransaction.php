<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuyTransaction extends Model
{
    protected $fillable = [
        'buyer_id',
        'traveler_id',
        'product_id',
        'quantity',
        'total_price',
        'payment_method_id',
        'payment_proof',
        'payment_status',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function traveler()
    {
        return $this->belongsTo(User::class, 'traveler_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function refund()
    {
        return $this->hasOne(Refund::class);
    }
}