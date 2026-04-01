<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuSection extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image'];

    public function restaurants()
    {
        return $this->belongsToMany(
            Restaurant::class,
            'menu_section_restaurant',
            'menu_section_id',
            'restaurant_id'
        );
    }

    public function subSections()
    {
        // تم تصحيح المفتاح الأجنبي هنا أيضاً
        return $this->hasMany(SubMenuSection::class, 'menu_section_id');
    }
}
