<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    // تعطيل الـ timestamps التلقائي لأن الجدول فيه بس created_at
    public $timestamps = false;

    protected $fillable = [
        'restaurant_id', 'image', 'content','title', 'cost',
         'status','is_active', 'start_date', 'end_date', 'created_at'
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function restaurant() {
        return $this->belongsTo(Restaurant::class);
    }
}
