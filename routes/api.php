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
use App\Http\Controllers\Api\Customer\CustomerAdController;
use App\Http\Controllers\Api\Customer\CustomerCartController;
use App\Http\Controllers\Api\Customer\CustomerFavoriteController;
use App\Http\Controllers\Api\Customer\CustomerOrderController;
use App\Http\Controllers\Api\Customer\CustomerReviewController;
use App\Http\Controllers\Api\Customer\CustomerSearchController;
use App\Http\Controllers\Api\Customer\CustomerSectionController;
use App\Http\Controllers\Api\Vendor\VendorExtraController;
use App\Http\Controllers\Api\Driver\DriverAuthController;
use App\Http\Controllers\Api\Driver\DriverEarningsController;
use App\Http\Controllers\Api\Driver\DriverProfileController;
use App\Http\Controllers\Api\Driver\DriverStatusController;
use App\Http\Controllers\Api\Driver\HomePageController;

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
        }); // admin middleware
    }); //admin prefix



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

        // //Extra
        Route::post('/extras', [VendorExtraController::class, 'store']);
        Route::put('/extras/{id}', [VendorExtraController::class, 'update']);
        Route::delete('/extras/{id}', [VendorExtraController::class, 'destroy']);

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
        // Route::get('/restaurants/{id}', [CustomerRestaurantController::class, 'show']);
        Route::get('/ads', [CustomerAdController::class, 'index']);


        // راوتات السلة (Cart)
        Route::prefix('cart')->group(function () {
            Route::get('/', [CustomerCartController::class, 'index']);
            Route::post('/items', [CustomerCartController::class, 'store']);
            Route::delete('/items/{id}', [CustomerCartController::class, 'destroy']);
            Route::delete('/', [CustomerCartController::class, 'clear']);
        });

        // راوت إنشاء الطلب
        Route::post('/checkout', [CustomerOrderController::class, 'checkout']);
        Route::put('/orders/{id}/cancel', [CustomerOrderController::class, 'cancel']);
        Route::get('/orders', [CustomerOrderController::class, 'index']);
        Route::get('/orders/{id}', [CustomerOrderController::class, 'show']);

        Route::get('/favorites', [CustomerFavoriteController::class, 'index']);
        Route::post('/favorites/restaurants/{id}', [CustomerFavoriteController::class, 'toggleRestaurant']);
        Route::post('/favorites/items/{id}', [CustomerFavoriteController::class, 'toggleItem']);

        Route::post('/search', [CustomerSearchController::class, 'searchMeals']);

        Route::get('/items/{id}', [MenuController::class, 'showItem']);
        Route::get('/trackOrder/{id}', [CustomerOrderController::class, 'trackOrder']);

        Route::get('/reviews', [CustomerReviewController::class, 'index']);
        Route::post('/orders/{orderId}/review', [CustomerReviewController::class, 'store']);
        Route::delete('/reviews/{id}', [CustomerReviewController::class, 'destroy']);



        // راوت إنشاء الطلب
        Route::post('/checkout', [CustomerOrderController::class, 'checkout']);

        Route::get('/orders', [CustomerOrderController::class, 'index']);
        Route::get('/orders/{id}', [CustomerOrderController::class, 'show']);

        Route::get('/favorites', [CustomerFavoriteController::class, 'index']);
        Route::post('/favorites/restaurants/{id}', [CustomerFavoriteController::class, 'toggleRestaurant']);
        Route::post('/favorites/items/{id}', [CustomerFavoriteController::class, 'toggleItem']);

        Route::post('/search', [CustomerSearchController::class, 'searchMeals']);

        Route::get('/items/{id}', [MenuController::class, 'showItem']);

        // إلغاء home القديم واستبداله بـ APIات الأقسام
        Route::get('/sections', [CustomerSectionController::class, 'index']);

        Route::get('/sections/{sectionId}/restaurants', [CustomerSectionController::class, 'restaurants']);


        Route::get('/restaurants', [CustomerRestaurantController::class, 'index']);
        Route::get('/restaurants/{id}', [CustomerRestaurantController::class, 'show']);
    });
});  // Closed the auth:sanctum middleware group and not.banned middleware group

Route::prefix('driver')->group(function () {

    Route::post('/register', [DriverAuthController::class, 'register']);


    Route::middleware(['auth:sanctum'])->group(function () {

        Route::put('/profile', [DriverProfileController::class, 'update']);
        Route::get('/profile', [DriverProfileController::class, 'showProfile']);
        Route::put('/status/online', [DriverStatusController::class, 'toggleOnline']);
        Route::put('/earnings', [DriverEarningsController::class, 'index']);

        Route::get('/transactions', [DriverEarningsController::class, 'transactions']);

        // عرض الطلبات المتاحة للسائق
        Route::get('available-orders', [HomePageController::class, 'getAvailableOrders']);
        // قبول طلب معين
        Route::post('orders/{id}/accept', [HomePageController::class, 'acceptOrder']);
        //رفض طلب معين
        Route::post('orders/{id}/reject', [HomePageController::class, 'rejectOrder']);
        // استلام الطلب من المطعم (يتطلب رفع صورة الفاتورة)
        Route::post('orders/{id}/pickup', [HomePageController::class, 'pickupOrder']);
        //تسليم الطلب للزبون
        Route::post('orders/{id}/deliver', [HomePageController::class, 'deliverOrder']);
    });
});
