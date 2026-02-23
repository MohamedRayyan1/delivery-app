<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'item_id', 'quantity', 'unit_price', 'total_price'];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function item() {
        return $this->belongsTo(MenuItem::class, 'item_id'); // لربطها بالوجبة الأصلية لمعرفة الاسم والصورة
    }
}
