<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    use HasFactory;

    // تحديد المفتاح الأساسي يدوياً لأنه ليس id
    protected $primaryKey = 'user_id';

    // منع الترقيم التلقائي لأن المفتاح يأتي من جدول المستخدمين
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'points',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
