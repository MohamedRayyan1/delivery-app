<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/run-queue', function () {
    try {
        // تشغيل الكييو لمعالجة العمليات لمرة واحدة أو لفترة قصيرة
        // --stop-when-empty يجعله يتوقف بمجرد انتهاء المهام لكي لا يستهلك موارد السيرفر
        Artisan::call('queue:work', [
            '--stop-when-empty' => true,
            '--force' => true
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'تم تشغيل معالج المهام بنجاح.',
            'log' => Artisan::output()
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/reset-and-seed-database', function () {
    try {
        // 1. مسح الجداول القديمة وإعادة بنائها من الصفر
        Artisan::call('migrate:reset', [
            '--force' => true
        ]);

        // 2. تعبئة البيانات الجديدة باستخدام DatabaseSeeder الذي نظمته
        Artisan::call('db:seed', [
            '--force' => true
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'تم مسح البيانات القديمة، إعادة بناء الجداول، وتعبئة البيانات الجديدة بنجاح!',
            'log' => Artisan::output()
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'حدث خطأ أثناء تصفير قاعدة البيانات: ' . $e->getMessage()
        ], 500);
    }
});
