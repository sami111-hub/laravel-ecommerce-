<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;
use App\Models\Product;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // إضافة/حذف من المفضلة (Toggle)
    public function toggle(Request $request, Product $product)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $message = 'تم الحذف من المفضلة';
            $inWishlist = false;
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id
            ]);
            $message = 'تم الإضافة للمفضلة ❤️';
            $inWishlist = true;
        }

        // دعم AJAX
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'inWishlist' => $inWishlist
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    // عرض المفضلة
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }
}
