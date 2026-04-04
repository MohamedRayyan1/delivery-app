<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $guarded = [];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // علاقة الوجبة (انتبه لحالة الحرف لتتطابق مع استخدامك في الـ Service)
    public function Item()
    {
        return $this->belongsTo(MenuItem::class, 'item_id');
    }

    // العلاقة الجديدة: الإضافات المرتبطة بهذه الوجبة تحديداً في السلة
    public function extras()
    {
        return $this->belongsToMany(
            ItemExtra::class,
            'cart_item_extras',
            'cart_item_id',
            'item_extra_id'
        );
    }
}
