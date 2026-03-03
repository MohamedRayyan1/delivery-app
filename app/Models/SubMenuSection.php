<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubMenuSection extends Model
{
    protected $fillable = ['section_id', 'name', 'image'];

    public function section() {
        return $this->belongsTo(MenuSection::class, 'section_id');
    }

    public function items() {
        // انتبه: الربط هنا صار مع sub_section_id حسب المخطط الجديد
        return $this->hasMany(MenuItem::class, 'sub_section_id');
    }

    
}
