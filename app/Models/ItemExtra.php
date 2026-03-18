<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_item_id',
        'name',
        'category',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
