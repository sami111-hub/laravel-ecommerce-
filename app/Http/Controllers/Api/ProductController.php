<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * قائمة المنتجات مع pagination + filter
     */
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'categories'])->where('is_active', true);

        // بحث
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
        }

        // فلتر حسب التصنيف
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // فلتر حسب البراند
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // فلتر حسب السعر
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // الترتيب
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $perPage = min($request->get('per_page', 12), 50);
        $products = $query->paginate($perPage);

        return ProductResource::collection($products);
    }

    /**
     * تفاصيل منتج واحد
     */
    public function show(Product $product)
    {
        $product->load([
            'brand',
            'categories',
            'reviews' => function ($query) {
                $query->where('is_approved', true)
                      ->with('user')
                      ->latest()
                      ->take(20);
            },
        ]);

        // منتجات مشابهة
        $related = Product::whereHas('categories', function ($q) use ($product) {
                $q->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with('brand')
            ->inRandomOrder()
            ->take(6)
            ->get();

        return response()->json([
            'success' => true,
            'product' => new ProductResource($product),
            'related' => ProductResource::collection($related),
        ]);
    }

    /**
     * بحث لحظي في المنتجات
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json(['success' => true, 'products' => []]);
        }

        $searchTerm = '%' . $query . '%';
        $products = Product::where('is_active', true)
            ->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            })
            ->with('brand')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'products' => ProductResource::collection($products),
        ]);
    }

    /**
     * المنتجات المميزة
     */
    public function featured()
    {
        $products = Product::where('is_active', true)
            ->with('brand')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'products' => ProductResource::collection($products),
        ]);
    }

    /**
     * أحدث المنتجات
     */
    public function latest()
    {
        $products = Product::where('is_active', true)
            ->with('brand')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'products' => ProductResource::collection($products),
        ]);
    }

    /**
     * عروض فلاش
     */
    public function flashDeals()
    {
        $products = Product::activeFlashDeals()
            ->where('is_active', true)
            ->with('brand')
            ->orderBy('flash_deal_ends_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'products' => ProductResource::collection($products),
        ]);
    }
}
