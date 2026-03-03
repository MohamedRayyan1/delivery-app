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

    public function __construct($model, string $column, string $path)
    {
        $this->model = $model;
        $this->column = $column;
        $this->path = $path;
    }

    public function handle()
    {
        if (!Storage::exists($this->path)) return;

        // تهيئة المكتبة (إصدار V3)
        $manager = new ImageManager(new Driver());

        // قراءة الصورة
        $image = $manager->read(Storage::get($this->path));

        // معالجة: تصغير العرض لـ 800 بكسل مع الحفاظ على التناسب
        $image->scale(width: 800);

        // ترميز الصورة بجودة 75% بصيغة Jpeg
        $encoded = $image->toJpeg(75);

        // حفظ الصورة فوق القديمة
        Storage::put($this->path, (string) $encoded);

        // تحديث الموديل بالمسار
        $this->model->update([$this->column => $this->path]);
    }
}
