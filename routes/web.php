<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OfferController as AdminOfferController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;

// صفحة auth التجريبية
Route::get('/auth', function () {
    return view('auth');
});

// Home - Hybrid Design (Bazzarry + Smart)
Route::get('/', function () {
    $categories = \App\Models\Category::all();
    $featuredProducts = \App\Models\Product::inRandomOrder()->take(8)->get();
    return view('home-hybrid', compact('categories', 'featuredProducts'));
})->name('home');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Public Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search'); // Live Search
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{category}', [ProductController::class, 'category'])->name('products.category');
Route::get('/offers', [OfferController::class, 'index'])->name('offers');
Route::get('/api/recommendations', [App\Http\Controllers\RecommendationController::class, 'getRecommendedProducts'])->name('api.recommendations');

// Compare Routes
Route::post('/compare/add/{product}', [CompareController::class, 'add'])->name('compare.add');
Route::post('/compare/remove/{product}', [CompareController::class, 'remove'])->name('compare.remove');
Route::get('/compare', [CompareController::class, 'index'])->name('compare.index');
Route::post('/compare/clear', [CompareController::class, 'clear'])->name('compare.clear');

// SEO Routes
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// Static Pages
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/return-policy', 'pages.return-policy')->name('return-policy');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');

// Categories
Route::get('/categories', function () {
    $categories = \App\Models\Category::withCount('products')->get();
    return view('pages.categories', compact('categories'));
})->name('categories.index');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
    
    // Wishlist Routes
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    
    // Order Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    
    // Coupon Routes
    Route::post('/coupons/validate', [CouponController::class, 'validateCoupon'])->name('coupons.validate');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // Reviews
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Notifications
    Route::post('/products/{product}/notify', [NotificationController::class, 'notifyWhenAvailable'])->name('products.notify');
});

// Admin Routes - Protected with Authentication & Permissions
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    // Dashboard - accessible to all authenticated users with admin role
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Products Management - requires product permissions
    // Create routes must come before show routes to avoid conflicts
    Route::middleware(['permission:create-products'])->group(function () {
        Route::get('products/create', [AdminProductController::class, 'create'])->name('products.create');
        Route::post('products', [AdminProductController::class, 'store'])->name('products.store');
    });
    
    Route::middleware(['permission:view-products'])->group(function () {
        Route::get('products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [AdminProductController::class, 'show'])->name('products.show');
    });
    
    Route::middleware(['permission:edit-products'])->group(function () {
        Route::get('products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [AdminProductController::class, 'update'])->name('products.update');
        Route::patch('products/{product}', [AdminProductController::class, 'update']);
    });
    
    Route::middleware(['permission:delete-products'])->group(function () {
        Route::delete('products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    });
    
    // Categories Management - requires category permissions
    // Create routes must come before show routes
    Route::middleware(['permission:create-categories'])->group(function () {
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    });
    
    Route::middleware(['permission:view-categories'])->group(function () {
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    });
    
    Route::middleware(['permission:edit-categories'])->group(function () {
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::patch('categories/{category}', [CategoryController::class, 'update']);
    });
    
    Route::middleware(['permission:delete-categories'])->group(function () {
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });
    
    // Offers Management - requires product permissions
    Route::middleware(['permission:view-products'])->group(function () {
        Route::resource('offers', AdminOfferController::class);
    });
    
    // Orders Management - requires order permissions
    Route::middleware(['permission:view-orders'])->group(function () {
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    });
    Route::middleware(['permission:edit-orders'])->group(function () {
        Route::get('orders/{order}/edit', [AdminOrderController::class, 'edit'])->name('orders.edit');
        Route::put('orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
        Route::patch('orders/{order}', [AdminOrderController::class, 'update']);
    });
    Route::middleware(['permission:delete-orders'])->group(function () {
        Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
    });
    
    // Users Management - requires user permissions
    // Create routes must come before show routes
    Route::middleware(['permission:create-users'])->group(function () {
        Route::get('users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('users', [UserManagementController::class, 'store'])->name('users.store');
    });
    
    Route::middleware(['permission:view-users'])->group(function () {
        Route::get('users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    });
    
    Route::middleware(['permission:edit-users'])->group(function () {
        Route::get('users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::patch('users/{user}', [UserManagementController::class, 'update']);
        Route::post('users/{user}/promote', [UserManagementController::class, 'promote'])->name('users.promote');
    });
    
    Route::middleware(['permission:delete-users'])->group(function () {
        Route::delete('users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });
    
    // Roles Management - requires role permissions (super-admin only)
    Route::middleware(['permission:view-roles'])->group(function () {
        Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show');
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });
    
    // Permissions Management - requires permission permissions (super-admin only)
    Route::middleware(['permission:view-permissions'])->group(function () {
        Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
        Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store');
        Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::get('permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show');
        Route::get('permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::put('permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    });
});
