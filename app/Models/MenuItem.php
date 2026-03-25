<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'sub_section_id', 'name', 'description',
        'price', 'discount_price', 'image',
        'is_featured', 'is_available'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_available' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    public function subSection() {
        return $this->belongsTo(SubMenuSection::class, 'sub_section_id');
    }

    public function extras()
    {
        return $this->hasMany(ItemExtra::class);
    }
    
    // دالة مساعدة لحساب السعر النهائي (مع الخصم)
    public function getFinalPriceAttribute() {
        return $this->discount_price ?: $this->price;
    }
}
