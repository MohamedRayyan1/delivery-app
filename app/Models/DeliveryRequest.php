<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'driver_id',
        'offered_delivery_fee',
        'required_vehicle_type',
        'status',
        'invoice_image'
    ];

    protected $casts = [
        'offered_delivery_fee' => 'decimal:2',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
