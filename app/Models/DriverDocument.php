<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverDocument extends Model
{
    protected $fillable = [
        'driver_id',
        'document_type',
        'file_path',
        'status'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
