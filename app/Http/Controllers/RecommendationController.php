<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;

class RecommendationController extends Controller
{
    public function getRecommendedProducts(Request $request)
    {
        $limit = $request->get('limit', 8);
        
        // منتجات مبنية على:
        // 1. المنتجات المشتراة معاً
        // 2. المنتجات من نفس الفئة
        // 3. المنتجات الأكثر مبيعاً
        
        $recommendedProducts = collect();
        
        if (Auth::check()) {
            // الحصول على منتجات المستخدم السابقة
            $userProductIds = Order::where('user_id', Auth::id())
                ->where('status', 'completed')
                ->with('items.product')
                ->get()
                ->flatMap(function($order) {
                    return $order->items->pluck('product_id');
                })
                ->unique()
                ->toArray();
            
            if (count($userProductIds) > 0) {
                // منتجات من نفس الفئات
                $userCategories = Product::whereIn('id', $userProductIds)
                    ->with('categories')
                    ->get()
                    ->flatMap(function($product) {
                        return $product->categories->pluck('id');
                    })
                    ->unique();
                
                $recommendedProducts = Product::whereHas('categories', function($query) use ($userCategories) {
                    $query->whereIn('categories.id', $userCategories);
                })
                ->whereNotIn('id', $userProductIds)
                ->where('stock', '>', 0)
                ->inRandomOrder()
                ->take($limit)
                ->get();
            }
        }
        
        // إذا لم يكن هناك منتجات موصى بها، نعرض الأكثر مبيعاً
        if ($recommendedProducts->isEmpty()) {
            $recommendedProducts = Product::where('stock', '>', 0)
                ->inRandomOrder()
                ->take($limit)
                ->get();
        }
        
        return response()->json([
            'products' => $recommendedProducts->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => number_format($product->price, 2),
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                    'url' => route('products.show', $product)
                ];
            })
        ]);
    }
}


