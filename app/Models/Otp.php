<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'code',
        'is_used',
        'expires_at',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    // دالة مساعدة للتحقق من الصلاحية
    public function isValid()
    {
        return ! $this->is_used && $this->expires_at->isFuture();
    }
}
