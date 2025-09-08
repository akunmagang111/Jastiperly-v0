<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
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

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function submiter()
    {
        return $this->belongsTo(User::class, 'submiter_id');
    }

    public function buyTransactions()
    {
        return $this->hasMany(BuyTransaction::class);
    }

    public function sendTransactions()
    {
        return $this->hasMany(SendTransaction::class);
    }
}