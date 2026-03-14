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
        // المطاعم المرتبطة بهذا القسم (قسم واحد يمكن أن يرتبط بعدة مطاعم)
        return $this->belongsToMany(
            Restaurant::class,
            'menu_section_restaurant',
            'menu_section_id',
            'restaurant_id'
        );
    }

    public function subSections() {
        return $this->hasMany(SubMenuSection::class, 'section_id');
    }

}
