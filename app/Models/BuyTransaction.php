<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        // Dari model lama
        'user_id',
        'amount',
        'status',

        // Dari ERD
        'buyer_id',
        'traveler_id',
        'product_id',
        'quantity',
        'total_price',
        'payment_method_id',
        'payment_proof',
        'payment_status',
    ];

    /**
     * ====================
     * Relasi
     * ====================
     */

    // Versi lama: transaksi milik user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Versi ERD
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
