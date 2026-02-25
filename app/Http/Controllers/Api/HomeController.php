<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PhoneResource;
use App\Http\Resources\OfferResource;
use App\Models\Product;
use App\Models\Category;
use App\Models\Phone;
use App\Models\Offer;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * بيانات الصفحة الرئيسية
     */
    public function index()
    {
        // الفئات الرئيسية
        $categories = Category::whereNull('parent_id')
            ->withCount('products')
            ->orderBy('id')
            ->take(10)
            ->get();

        // المنتجات المميزة - آمن من الأعمدة المفقودة
        $featuredProducts = Product::when(
                \Illuminate\Support\Facades\Schema::hasColumn('products', 'is_featured'),
                fn($q) => $q->where('is_featured', true)
            )
            ->when(
                \Illuminate\Support\Facades\Schema::hasColumn('products', 'is_active'),
                fn($q) => $q->where('is_active', true)
            )
            ->with(['category', 'brand'])
            ->take(8)
            ->get();

        // أحدث المنتجات
        $latestProducts = Product::where('is_active', true)
            ->with(['category', 'brand'])
            ->latest()
            ->take(8)
            ->get();

        // عروض الفلاش
        $flashDeals = Product::where('is_active', true)
            ->whereNotNull('discount_price')
            ->where('discount_price', '>', 0)
            ->with(['category', 'brand'])
            ->orderByRaw('((price - discount_price) / price) DESC')
            ->take(6)
            ->get();

        // أحدث الموبايلات
        $latestPhones = Phone::with(['brand', 'specs'])
            ->latest()
            ->take(6)
            ->get();

        // العروض النشطة
        $activeOffers = Offer::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('discount_percentage', 'desc')
            ->take(4)
            ->get();

        // إعدادات الموقع (سلايدر / بانرات)
        $settings = SiteSetting::whereIn('key', [
            'slider_images',
            'banner_1',
            'banner_2',
            'announcement',
            'store_name',
            'store_phone',
            'store_email',
        ])->get()->pluck('value', 'key');

        return response()->json([
            'success' => true,
            'data' => [
                'categories' => CategoryResource::collection($categories),
                'featured_products' => ProductResource::collection($featuredProducts),
                'latest_products' => ProductResource::collection($latestProducts),
                'flash_deals' => ProductResource::collection($flashDeals),
                'latest_phones' => PhoneResource::collection($latestPhones),
                'active_offers' => OfferResource::collection($activeOffers),
                'settings' => $settings,
            ],
        ]);
    }

    /**
     * بيانات الموقع العامة (يمكن للتطبيق تخزينها مؤقتاً)
     */
    public function settings()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    /**
     * أسعار الصرف للعملات
     * GET /api/v1/currencies
     */
    public function currencies()
    {
        $sarRate = floatval(SiteSetting::get('exchange_rate_sar', '3.75'));
        $yerRate = floatval(SiteSetting::get('exchange_rate_yer', '535'));

        return response()->json([
            'success' => true,
            'data' => [
                'base_currency' => 'USD',
                'currencies' => [
                    [
                        'code' => 'USD',
                        'name' => 'دولار أمريكي',
                        'name_en' => 'US Dollar',
                        'symbol' => '$',
                        'rate' => 1.0,
                    ],
                    [
                        'code' => 'SAR',
                        'name' => 'ريال سعودي',
                        'name_en' => 'Saudi Riyal',
                        'symbol' => 'ر.س',
                        'rate' => $sarRate,
                    ],
                    [
                        'code' => 'YER',
                        'name' => 'ريال يمني',
                        'name_en' => 'Yemeni Rial',
                        'symbol' => 'ر.ي',
                        'rate' => $yerRate,
                    ],
                ],
            ],
        ]);
    }
}
