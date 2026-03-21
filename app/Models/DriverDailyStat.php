<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverDailyStat extends Model
{
    protected $fillable = [
        'driver_id',
        'stat_date',
        'earnings',
        'completed_orders',
        'rating_sum',
        'rating_count',
    ];

    protected $casts = [
        'stat_date' => 'date',
        'earnings' => 'decimal:2',
        'rating_sum' => 'decimal:2',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
}
