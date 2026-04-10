<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Cache;
use App\Models\Ad;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    $now = now();

    $activated = Ad::where('status', 'approved')
        ->where('start_date', '<=', $now)
        ->where('end_date', '>=', $now)
        ->where('is_active', false)
        ->update(['is_active' => true]);

    $expired = Ad::where('end_date', '<', $now)
        ->where('is_active', true)
        ->update(['is_active' => false, 'status' => 'expired']);

    if ($activated > 0 || $expired > 0) {
        Cache::forget('active_ads');
    }
})->everyFiveMinutes();
