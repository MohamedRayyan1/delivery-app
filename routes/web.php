<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/reset-and-seed-database', function () {
    try {
        // 1. مسح الجداول القديمة وإعادة بنائها من الصفر
        Artisan::call('migrate:refresh', [
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
