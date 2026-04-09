<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'driver_id',
        'address_id',
        'coupon_id',
        'delivery_confirmation_code',
        'status',
        'payment_method',
        'payment_status',
        'transaction_ref',
        'subtotal',
        'delivery_fee',
        'discount_amount',
        'grand_total',
        'applied_restaurant_commission',
        'applied_driver_share',
        'picked_up_at',
        'delivered_at',
        'paid_at'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'applied_restaurant_commission' => 'decimal:2',
        'applied_driver_share' => 'decimal:2',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    // --- العلاقات ---
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function deliveryRequest()
    {
        // تفترض هذه العلاقة أن جدول delivery_requests يحتوي على order_id
        return $this->hasOne(DeliveryRequest::class);
    }

    // --- الحسابات الديناميكية (Accessors) ---

    // 1. حساب أرباح التطبيق (App Earnings)
    // المعادلة: عمولة المطعم + (حصتنا من التوصيل إن وجدت)
    protected function appEarnings(): Attribute
    {
        return Attribute::make(
            get: function () {
                $commissionFromRestaurant = ($this->subtotal * $this->applied_restaurant_commission) / 100;
                $driverShareAmount = ($this->delivery_fee * $this->applied_driver_share) / 100;
                $commissionFromDelivery = $this->delivery_fee - $driverShareAmount;

                return round($commissionFromRestaurant + $commissionFromDelivery, 2);
            }
        );
    }

    // 2. حساب أرباح المطعم (Restaurant Earnings)
    // المعادلة: المجموع الفرعي - عمولة التطبيق
    protected function restaurantEarnings(): Attribute
    {
        return Attribute::make(
            get: function () {
                $commission = ($this->subtotal * $this->applied_restaurant_commission) / 100;
                return round($this->subtotal - $commission, 2);
            }
        );
    }

    // 3. حساب أرباح السائق (Driver Earnings)
    // المعادلة: حصته من التوصيل (عادة 100%)
    protected function driverEarnings(): Attribute
    {
        return Attribute::make(
            get: function () {
                return round(($this->delivery_fee * $this->applied_driver_share) / 100, 2);
            }
        );
    }
}
