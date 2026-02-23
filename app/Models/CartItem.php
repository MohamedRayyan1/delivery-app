<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'item_id', 'quantity', 'notes'];

    public function item() { // الوجبة
        return $this->belongsTo(MenuItem::class, 'item_id');
    }
}
