<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'manager_user_id',
        'name',
        'governorate',
        'city',
        'status',
        'logo',
        'lat',
        'lng',
        'cover_image',
        'description',
        'rating',
        'delivery_cost',
        'min_order_price',
        'delivery_time',
        'is_featured'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'rating' => 'decimal:2',
        'delivery_cost' => 'decimal:2',
        'min_order_price' => 'decimal:2',
    ];

    // العلاقات
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_user_id');
    }

    /**
     * الأقسام المرتبطة بالمطعم عبر الجدول الوسيط menu_section_restaurant
     * (قسم واحد يمكن أن يحتوي عدة مطاعم، ومطعم يمكن أن يرتبط بعدة أقسام)
     */
    public function sections()
    {
        return $this->belongsToMany(
            MenuSection::class,
            'menu_section_restaurant',
            'restaurant_id',
            'menu_section_id'
        );
    }
    public function subMenuSections()
    {
        return $this->hasMany(SubMenuSection::class, 'restaurant_id');
    }

    /**
     * الطلبات المرتبطة بالمطعم (تُستخدم لحساب الأكثر طلباً)
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * اسم قديم للعلاقة للإبقاء على التوافق مع أي استخدامات سابقة
     */
    public function menuSections()
    {
        return $this->sections();
    }


    /**
     * التقييمات الخاصة بالمطعم
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
