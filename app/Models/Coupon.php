<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'discount_type', 'value',
        'min_order_price', 'expiry_date', 'usage_limit'
    ];

    protected $casts = [
        'expiry_date' => 'datetime',
        'value' => 'decimal:2',
    ];

    // دالة للتحقق من الصلاحية
    public function isValid() {
        return $this->expiry_date->isFuture();
    }
}
