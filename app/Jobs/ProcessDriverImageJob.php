<?php

namespace App\Jobs;

use App\Models\Driver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessDriverImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $driverId,
        public string $tempPath
    ) {}

    public function handle(): void
    {
        try {
            Log::info(" بدء معالجة صورة الرخصة للسائق ID: {$this->driverId} | المسار المؤقت: {$this->tempPath}");

            $driver = Driver::findOrFail($this->driverId);

            if (!Storage::disk('public')->exists($this->tempPath)) {
                Log::warning("⚠️ الملف المؤقت غير موجود: {$this->tempPath}");
                return;
            }

            // إنشاء المسار الدائم
            $newPath = 'drivers/licenses/' . $driver->user_id . '/' . basename($this->tempPath);

            // نقل الملف (هذا يحذف الملف المؤقت تلقائياً)
            Storage::disk('public')->move($this->tempPath, $newPath);

            // تحديث قاعدة البيانات
            $driver->update(['license_image' => $newPath]);

            Log::info(" تم نقل الصورة بنجاح إلى: {$newPath}");

        } catch (\Exception $e) {
            Log::error(" خطأ في معالجة صورة الرخصة للسائق {$this->driverId}: " . $e->getMessage());
            throw $e;
        }
    }
}
