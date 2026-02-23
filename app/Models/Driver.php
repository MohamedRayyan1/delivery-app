<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'user_id', 'is_online', 'account_status',
        'vehicle_type', 'vehicle_plate_number',
        'license_image', 'current_lat', 'current_lng'
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'current_lat' => 'decimal:8',
        'current_lng' => 'decimal:8',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    // سنضيف علاقات الطلبات والتقييمات لاحقاً
}
