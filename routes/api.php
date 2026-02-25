<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PhoneController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\HomeController;

/*
|--------------------------------------------------------------------------
| API Routes - الإصدار الأول v1
|--------------------------------------------------------------------------
| جميع الروابط تبدأ بـ /api/v1/
| الروابط المحمية تحتاج توكن Sanctum
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ============================================================
    //  الروابط العامة (بدون تسجيل دخول)
    // ============================================================

    // --- المصادقة ---
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // --- الصفحة الرئيسية ---
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/settings', [HomeController::class, 'settings']);
    Route::get('/currencies', [HomeController::class, 'currencies']);

    // --- المنتجات ---
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/featured', [ProductController::class, 'featured']);
    Route::get('/products/latest', [ProductController::class, 'latest']);
    Route::get('/products/flash-deals', [ProductController::class, 'flashDeals']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    // --- الفئات ---
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}/products', [CategoryController::class, 'products']);

    // --- الموبايلات ---
    Route::get('/phones', [PhoneController::class, 'index']);
    Route::get('/phones/latest', [PhoneController::class, 'latest']);
    Route::get('/phones/popular', [PhoneController::class, 'popular']);
    Route::get('/phones/search', [PhoneController::class, 'search']);
    Route::get('/phones/compare', [PhoneController::class, 'compare']);
    Route::get('/phones/brands', [PhoneController::class, 'brands']);
    Route::get('/phones/brands/{brand}', [PhoneController::class, 'brandPhones']);
    Route::get('/phones/{phone}', [PhoneController::class, 'show']);

    // --- العروض ---
    Route::get('/offers', [OfferController::class, 'index']);

    // --- التقييمات (قراءة فقط) ---
    Route::get('/products/{product}/reviews', [ReviewController::class, 'index']);

    // ============================================================
    //  الروابط المحمية (تحتاج تسجيل دخول)
    // ============================================================

    Route::middleware('auth:sanctum')->group(function () {

        // --- اختبار التوكن ---
        Route::get('/test-auth', function () {
            return response()->json([
                'success' => true,
                'message' => 'التوكن صحيح ✅',
                'user' => auth()->user(),
            ]);
        });

        // --- الحساب ---
        Route::get('/user', [AuthController::class, 'user']);
        Route::put('/user/profile', [AuthController::class, 'updateProfile']);
        Route::put('/user/password', [AuthController::class, 'changePassword']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // --- السلة ---
        Route::get('/cart', [CartController::class, 'index']);
        Route::get('/cart/count', [CartController::class, 'count']);
        Route::post('/cart', [CartController::class, 'store']);
        Route::put('/cart/{cart}', [CartController::class, 'update']);
        Route::delete('/cart/{cart}', [CartController::class, 'destroy']);

        // --- الطلبات ---
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::put('/orders/{order}/cancel', [OrderController::class, 'cancel']);

        // --- المفضلة ---
        Route::get('/wishlist', [WishlistController::class, 'index']);
        Route::post('/wishlist/toggle', [WishlistController::class, 'toggle']);

        // --- التقييمات ---
        Route::post('/products/{product}/reviews', [ReviewController::class, 'store']);
        Route::put('/reviews/{review}', [ReviewController::class, 'update']);
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

        // --- العناوين ---
        Route::get('/addresses', [AddressController::class, 'index']);
        Route::post('/addresses', [AddressController::class, 'store']);
        Route::put('/addresses/{address}', [AddressController::class, 'update']);
        Route::delete('/addresses/{address}', [AddressController::class, 'destroy']);
        Route::put('/addresses/{address}/default', [AddressController::class, 'setDefault']);

        // --- الكوبونات ---
        Route::post('/coupons/validate', [OfferController::class, 'validateCoupon']);
    });
});
