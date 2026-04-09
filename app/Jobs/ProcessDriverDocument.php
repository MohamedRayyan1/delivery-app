<?php

// app/Jobs/ProcessDriverDocument.php

namespace App\Jobs;

use App\Models\DriverDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessDriverDocument implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $driverId;
    protected $type;
    protected $path;

    public function __construct($driverId, $type, $path)
    {
        $this->driverId = $driverId;
        $this->type = $type;
        $this->path = $path;
    }

    public function handle()
    {
        // إنشاء السجل في قاعدة البيانات بعد التأكد من وجود الملف
        DriverDocument::updateOrCreate(
            [
                'driver_id'     => $this->driverId,
                'document_type' => $this->type,
            ],
            [
                'file_path' => $this->path,
                'status'    => 'pending',
            ]
        );
    }
}
