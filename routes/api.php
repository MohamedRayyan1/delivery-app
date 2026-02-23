<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\ProfileController;

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

});  // Closed the auth middleware group
