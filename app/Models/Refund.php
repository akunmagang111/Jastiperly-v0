<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    /**
     * Nama tabel
     */
    protected $table = 'refunds';

    /**
     * Kolom yang bisa diisi mass-assignment
     */
    protected $fillable = [
        'buy_transaction_id',
        'reason',
        'status',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'status' => 'string',
    ];

    /**
     * ====================
     * Relasi
     * ====================
     */

    // Relasi ke Transaksi Pembelian (buy_transactions)
    public function buyTransaction()
    {
        return $this->belongsTo(BuyTransaction::class, 'buy_transaction_id');
    }
}
