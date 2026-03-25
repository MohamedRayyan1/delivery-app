<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id', 'label', 'street', 'details',
        'floor', 'phone', 'lat', 'lng', 'is_default'
    ];

    protected $casts = [
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
        'is_default' => 'boolean',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }


}
