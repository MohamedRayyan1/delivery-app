<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\Admin\AdminAdController;
use App\Http\Controllers\Api\Admin\AdminCouponController;
use App\Http\Controllers\Api\Customer\CustomerRestaurantController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Vendor\MenuController;
use App\Http\Controllers\Api\Vendor\VendorAdController;
use App\Http\Controllers\Api\Vendor\VendorProfileController;
use App\Http\Controllers\Api\Customer\CustomerSectionController;

// 1. (Public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// إرسال الـ OTP أصبح يتم بشكل داخلي ضمن مسارات التسجيل وتسجيل الدخول
Route::post('/otp/verify', [OtpController::class, 'verify']);
Route::post('/otp/send', [OtpController::class, 'send']);

// 2. راوتات محمية (Protected) - تحتاج توكن (المحظورون ممنوعون)
Route::middleware(['auth:sanctum', 'not.banned'])->group(function () {

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

            // Sections
            Route::post('/sections', [MenuController::class, 'storeSection']);
            Route::put('/sections/{id}', [MenuController::class, 'updateSection']);
            Route::delete('/sections/{id}', [MenuController::class, 'destroySection']);

            // حظر / إلغاء حظر مستخدم من قبل الأدمن (بدون حذف)
            Route::patch('users/{id}/ban', [ProfileController::class, 'banUser']);
            Route::patch('users/{id}/unban', [ProfileController::class, 'unbanUser']);
            //ads
            Route::prefix('ads')->group(function () {
                Route::get('/pending', [AdminAdController::class, 'index']);
                Route::patch('/{id}/quote', [AdminAdController::class, 'quote']);
                Route::patch('/{id}/approve', [AdminAdController::class, 'approve']);
                Route::patch('/{id}/reject', [AdminAdController::class, 'reject']);
            });
            Route::prefix('coupons')->group(function () {
                Route::get('/', [AdminCouponController::class, 'index']);
                Route::post('/', [AdminCouponController::class, 'store']);
                Route::put('/{id}', [AdminCouponController::class, 'update']);
                Route::delete('/{id}', [AdminCouponController::class, 'destroy']);
             });
        }); //
    }); //



    //menu
    Route::get('vendor/menu/{restaurant_id}', [MenuController::class, 'index']);

    //vendor
    Route::middleware(['check.restaurant.owner'])->prefix('vendor')->group(function () {

        Route::get('/profile', [VendorProfileController::class, 'show']);
        Route::put('/profile', [VendorProfileController::class, 'update']);



        // Sub-Sections
        Route::post('/sub-sections', [MenuController::class, 'storeSubSection']);
        Route::put('/sub-sections/{id}', [MenuController::class, 'updateSubSection']);
        Route::delete('/sub-sections/{id}', [MenuController::class, 'destroySubSection']);

        // Items

        Route::post('/items', [MenuController::class, 'storeItem']);
        Route::put('/items/{id}', [MenuController::class, 'updateItem']);
        Route::delete('/items/{id}', [MenuController::class, 'destroyItem']);
        //ads
        Route::prefix('ads')->group(function () {
            Route::get('/', [VendorAdController::class, 'index']);
            Route::post('/', [VendorAdController::class, 'store']);
            Route::post('/{id}', [VendorAdController::class, 'update']);
            Route::delete('/{id}', [VendorAdController::class, 'destroy']);
        });
    }); // Closed the vendor middleware group

    // customer
    Route::prefix('customer')->group(function () {
        // إلغاء home القديم واستبداله بـ APIات الأقسام
        Route::get('/sections', [CustomerSectionController::class, 'index']);

        Route::get('/sections/{sectionId}/restaurants', [CustomerSectionController::class, 'restaurants']);

        Route::get('/restaurants', [CustomerRestaurantController::class, 'index']);
        Route::get('/restaurants/{id}', [CustomerRestaurantController::class, 'show']);
    });
});  // Closed the auth:sanctum middleware group

