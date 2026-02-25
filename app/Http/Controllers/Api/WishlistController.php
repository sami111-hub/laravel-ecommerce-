<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * عرض المفضلة
     */
    public function index(Request $request)
    {
        $wishlists = Wishlist::where('user_id', $request->user()->id)
            ->with('product.brand')
            ->latest()
            ->get();

        $products = $wishlists->map(fn($w) => $w->product)->filter();

        return response()->json([
            'success' => true,
            'products' => ProductResource::collection($products),
        ]);
    }

    /**
     * إضافة/إزالة من المفضلة
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $wishlist = Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json([
                'success' => true,
                'message' => 'تم الحذف من المفضلة',
                'in_wishlist' => false,
            ]);
        }

        Wishlist::create([
            'user_id' => $request->user()->id,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم الإضافة للمفضلة',
            'in_wishlist' => true,
        ]);
    }
}
