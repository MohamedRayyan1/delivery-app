<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuSection extends Model
{
    protected $fillable = ['restaurant_id', 'name', 'image'];

    public function restaurant() {
        return $this->belongsTo(Restaurant::class);
    }

    public function subSections() {
        return $this->hasMany(SubMenuSection::class, 'section_id');
    }
}
