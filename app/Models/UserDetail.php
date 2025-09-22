<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = [
        'user_id',
        'verified_type',
        'name',
        'phone',
        'address',
        'date_birth',
        'gender',
        'bank_name',
        'bank_number',
        'id_card_image',
        'pasport_image',
        'account_image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}