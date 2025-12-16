<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class CompareController extends Controller
{
    public function add(Request $request, Product $product)
    {
        $compareList = session('compare', []);
        
        if (count($compareList) >= 4) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'يمكنك مقارنة 4 منتجات كحد أقصى'
                ], 422);
            }
            return redirect()->back()->with('error', 'يمكنك مقارنة 4 منتجات كحد أقصى');
        }
        
        if (!in_array($product->id, $compareList)) {
            $compareList[] = $product->id;
            session(['compare' => $compareList]);
        }
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المنتج للمقارنة',
                'count' => count($compareList)
            ]);
        }
        
        return redirect()->back()->with('success', 'تم إضافة المنتج للمقارنة');
    }

    public function remove(Request $request, Product $product)
    {
        $compareList = session('compare', []);
        $compareList = array_values(array_diff($compareList, [$product->id]));
        session(['compare' => $compareList]);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إزالة المنتج من المقارنة',
                'count' => count($compareList)
            ]);
        }
        
        return redirect()->back()->with('success', 'تم إزالة المنتج من المقارنة');
    }

    public function index()
    {
        $compareList = session('compare', []);
        
        if (empty($compareList)) {
            return view('products.compare', ['products' => collect()]);
        }
        
        $products = Product::whereIn('id', $compareList)
            ->with(['brand', 'categories', 'reviews' => function($query) {
                $query->where('is_approved', true);
            }])
            ->get()
            ->sortBy(function($product) use ($compareList) {
                return array_search($product->id, $compareList);
            })
            ->values();
        
        return view('products.compare', compact('products'));
    }

    public function clear()
    {
        session(['compare' => []]);
        return redirect()->back()->with('success', 'تم مسح قائمة المقارنة');
    }
}

