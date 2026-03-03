<?php

use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Vendor\MenuController;
use App\Http\Controllers\Api\Vendor\VendorProfileController;

// 1. (Public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/otp/send', [OtpController::class, 'send']);
Route::post('/otp/verify', [OtpController::class, 'verify']);

// 2. راوتات محمية (Protected) - تحتاج توكن
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    //addresses
    Route::prefix('addresses')->group(function () {
        Route::get('/', [AddressController::class, 'index']);
        Route::post('/', [AddressController::class, 'store']);
        Route::put('/{id}', [AddressController::class, 'update']);
        Route::delete('/{id}', [AddressController::class, 'destroy']);
        Route::patch('/{id}/default', [AddressController::class, 'setDefault']);

     });
     //profile
     Route::prefix('profile')->group(function () {
         Route::get('/', [ProfileController::class, 'show']);
         Route::put('/', [ProfileController::class, 'update']);
         Route::patch('/fcm-token', [ProfileController::class, 'updateFcmToken']);
         Route::delete('/', [ProfileController::class, 'destroy']);
    });

    Route::prefix('admin')->group(function () {

        Route::get('restaurants/{id}', [RestaurantController::class, 'show']);
        Route::get('restaurants', [RestaurantController::class, 'index']);

        Route::middleware('admin')->group(function () {
            Route::post('restaurants', [RestaurantController::class, 'store']);
            Route::put('restaurants/{id}', [RestaurantController::class, 'update']);
            Route::delete('restaurants/{id}', [RestaurantController::class, 'destroy']);
        });
    });



    //menu
    Route::get('vendor/menu/{restaurant_id}', [MenuController::class, 'index']);

    Route::middleware(['check.restaurant.owner'])->prefix('vendor')->group(function () {

    Route::get('/profile', [VendorProfileController::class, 'show']);
    Route::put('/profile', [VendorProfileController::class, 'update']);


    // Sections
    Route::post('/sections', [MenuController::class, 'storeSection']);
    Route::put('/sections/{id}', [MenuController::class, 'updateSection']);
    Route::delete('/sections/{id}', [MenuController::class, 'destroySection']);

    // Sub-Sections
    Route::post('/sub-sections', [MenuController::class, 'storeSubSection']);
    Route::put('/sub-sections/{id}', [MenuController::class, 'updateSubSection']);
    Route::delete('/sub-sections/{id}', [MenuController::class, 'destroySubSection']);

    // Items
    Route::post('/items', [MenuController::class, 'storeItem']);
    Route::put('/items/{id}', [MenuController::class, 'updateItem']);
    Route::delete('/items/{id}', [MenuController::class, 'destroyItem']);

});






















Route::get('/init-db', function() {
    try {
        Artisan::call('migrate --force');
        return "✅ Tables created successfully: " . Artisan::output();
    } catch (\Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});

Route::get('/init-storage', function() {
    Artisan::call('storage:link');
    return "✅ Storage link created!";
});

});  // Closed the auth middleware group
