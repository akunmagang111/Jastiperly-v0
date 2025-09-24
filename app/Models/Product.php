<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'submiter_id',
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'status',
        'approval',
    ];

    protected $attributes = [
        'status'   => 'inactive',
        'approval' => 'pending',
    ];

    /**
     * Relasi ke user (traveler / customer) sebagai pengunggah produk
     */
    public function submiter()
    {
        return $this->belongsTo(User::class, 'submiter_id')->withDefault();
    }

    /**
     * Relasi ke kategori produk
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id')->withDefault();
    }

    /**
     * Relasi ke transaksi pembelian
     */
    public function buyTransactions()
    {
        return $this->hasMany(BuyTransaction::class, 'product_id');
    }

    /**
     * Relasi ke transaksi pengiriman
     */
    public function sendTransactions()
    {
        return $this->hasMany(SendTransaction::class, 'product_id');
    }
}
