<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProcessImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $model;
    protected $column;
    protected $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function handle()
{
    if (!Storage::disk('public')->exists($this->path)) {
        return;
    }

    $manager = new ImageManager(new Driver());

    $fullPath = Storage::disk('public')->path($this->path);

    $image = $manager->read($fullPath);

    $image->scale(width: 800);

    $extension = pathinfo($this->path, PATHINFO_EXTENSION);

    if ($extension === 'png') {
        $encoded = $image->toPng();
    } else {
        $encoded = $image->toJpeg(75);
    }

    $tempPath = $this->path . '.tmp';

    Storage::disk('public')->put($tempPath, (string) $encoded);
    Storage::disk('public')->move($tempPath, $this->path);
}


//    public function handle()
// {
//     // التأكد أن الملف موجود فيزيائياً
//     if (!Storage::disk('public')->exists($this->path)) return;

//     $manager = new ImageManager(new Driver());

//     // معالجة مكثفة: قراءة، تصغير، تغيير جودة، تحويل صيغة
//     $image = $manager->read(Storage::disk('public')->get($this->path));
//     $image->scale(width: 800);
//     $encoded = $image->toJpeg(75); // تقليل الحجم بنسبة كبيرة

//     // حفظ الملف المعالج فوق الملف الأصلي الخام
//     Storage::disk('public')->put($this->path, (string) $encoded);

//     // ملاحظة: لا حاجة لتحديث قاعدة البيانات لأن المسار لم يتغير!
// }
}
