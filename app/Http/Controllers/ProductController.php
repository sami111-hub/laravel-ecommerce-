<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Search - إصلاح مشكلة orWhere
        if ($request->has('search') && $request->search) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
        }

        // Filter by category (supports both ID and slug)
        if ($request->has('category') && $request->category) {
            $catValue = $request->category;
            $query->whereHas('categories', function($q) use ($catValue) {
                if (is_numeric($catValue)) {
                    $q->where('categories.id', $catValue);
                } else {
                    $q->where('categories.slug', $catValue);
                }
            });
        }

        // Filter by brand
        if ($request->has('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Sort
        if ($request->has('sort')) {
            switch($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'latest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $products = $query->with(['brand', 'images'])->paginate(12);
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $brands = Brand::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function show(Product $product)
    {
        $product->load(['brand', 'categories', 'images', 'variants' => function($query) {
            $query->where('is_active', true)->orderBy('model_name');
        }, 'reviews' => function($query) {
            $query->where('is_approved', true)
                  ->with('user')
                  ->latest()
                  ->take(10);
        }]);
        
        // منتجات مشابهة محسّنة (حسب التصنيف + الماركة + سعر قريب)
        $categoryIds = $product->categories->pluck('id');
        
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where(function($q) use ($product, $categoryIds) {
                // نفس التصنيف
                $q->whereHas('categories', function($sub) use ($categoryIds) {
                    $sub->whereIn('categories.id', $categoryIds);
                });
                // أو نفس الماركة
                if ($product->brand_id) {
                    $q->orWhere('brand_id', $product->brand_id);
                }
            })
            ->with(['brand', 'images'])
            ->inRandomOrder()
            ->take(8)
            ->get();
        
        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function category(Category $category)
    {
        $query = $category->products()->with(['brand', 'images']);
        
        // Filter by brand within category
        if (request('brand')) {
            $query->where('brand_id', request('brand'));
        }
        
        // Sort
        if (request('sort')) {
            switch(request('sort')) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'latest':
                    $query->orderBy('products.created_at', 'desc');
                    break;
                default:
                    $query->orderBy('products.id', 'desc');
            }
        } else {
            $query->orderBy('products.id', 'desc');
        }
        
        $products = $query->paginate(12);
        
        // الماركات الموجودة في هذا القسم فقط
        $brandIds = $category->products()->pluck('brand_id')->unique()->filter();
        $brands = Brand::whereIn('id', $brandIds)->where('is_active', true)->get();
        
        $categories = Category::where('is_active', true)->orderBy('order')->get();

        return view('products.category', compact('category', 'products', 'categories', 'brands'));
    }

    // Live Search API
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['products' => []]);
        }

        $searchTerm = '%' . $query . '%';
        $products = Product::where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            })
            ->with('brand')
            ->take(8)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => number_format($product->price, 2),
                    'image_url' => $product->image
                ];
            });

        return response()->json(['products' => $products]);
    }
}
