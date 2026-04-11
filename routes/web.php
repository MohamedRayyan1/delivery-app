<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


Route::get('/', function () {
    return view('welcome');
});

// Route::get('/debug-queue', function () {
//     Artisan::call('config:clear');
//     Artisan::call('cache:clear');

//     $isQueued = in_array(
//         \Illuminate\Contracts\Queue\ShouldQueue::class,
//         class_implements(\App\Jobs\ProcessImageJob::class)
//     );

//     return response()->json([
//         'config_cache_cleared' => true,
//         'queue_driver' => config('queue.default'),
//         'env_queue' => env('QUEUE_CONNECTION'),
//         'job_is_queueable' => $isQueued,
//     ]);
// });


Route::get('/queue/run-once', function () {
    Artisan::call('queue:work', [
        '--stop-when-empty' => true,
        '--force' => true
    ]);

    return response()->json([
        'status' => 'queue processed',
        'output' => Artisan::output()
    ]);
});

Route::get('/queue/work', function () {
    Artisan::call('queue:work', [
        '--force' => true
    ]);

    return response()->json([
        'status' => 'queue worker started'
    ]);
});



Route::get('/db/reset', function () {
    Artisan::call('migrate:fresh', ['--force' => true]);
    Artisan::call('db:seed', ['--force' => true]);

    return response()->json([
        'status' => 'database reset done',
        'output' => Artisan::output()
    ]);
});


Route::get('/storage-link', function () {


    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');

        return response()->json([
            'status' => 'success',
            'message' => 'Storage linked successfully',
            'output' => \Illuminate\Support\Facades\Artisan::output()
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});


