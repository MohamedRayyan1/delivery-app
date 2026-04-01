<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubMenuSection extends Model
{
    protected $fillable = ['restaurant_id', 'menu_section_id', 'name', 'image'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    public function section()
    {
        return $this->belongsTo(MenuSection::class, 'menu_section_id');
    }

    public function items()
    {
        return $this->hasMany(MenuItem::class, 'sub_section_id');
    }
}
