<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'manager_user_id', 'name', 'governorate', 'city', 'status',
        'logo', 'cover_image', 'description', 'rating',
        'delivery_cost', 'min_order_price', 'delivery_time', 'is_featured'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'rating' => 'decimal:2',
        'delivery_cost' => 'decimal:2',
        'min_order_price' => 'decimal:2',
    ];

    // العلاقات
    public function manager() {
        return $this->belongsTo(User::class, 'manager_user_id');
    }

    public function menuSections() {
        return $this->hasMany(MenuSection::class);
    }
}
