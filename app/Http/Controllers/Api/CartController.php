<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * عرض السلة
     */
    public function index(Request $request)
    {
        $carts = Cart::where('user_id', $request->user()->id)
            ->with(['product.brand'])
            ->get();

        // إزالة المنتجات غير المتوفرة
        $carts = $carts->filter(function ($cart) {
            if (!$cart->product || $cart->product->stock <= 0) {
                $cart->delete();
                return false;
            }
            if ($cart->quantity > $cart->product->stock) {
                $cart->quantity = $cart->product->stock;
                $cart->save();
            }
            return true;
        });

        $subtotal = $carts->sum(fn($cart) => $cart->product->price * $cart->quantity);

        return response()->json([
            'success' => true,
            'cart' => CartResource::collection($carts->values()),
            'subtotal' => (float) $subtotal,
            'items_count' => $carts->sum('quantity'),
        ]);
    }

    /**
     * عدد عناصر السلة
     */
    public function count(Request $request)
    {
        $count = Cart::where('user_id', $request->user()->id)->sum('quantity');

        return response()->json([
            'success' => true,
            'count' => (int) $count,
        ]);
    }

    /**
     * إضافة منتج للسلة
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->input('quantity', 1);

        // فحص المخزون
        if ($product->stock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => $product->stock > 0
                    ? "المخزون المتاح: {$product->stock} قطعة فقط"
                    : 'المنتج غير متوفر حالياً',
            ], 422);
        }

        $cart = Cart::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {
            $newQuantity = $cart->quantity + $quantity;

            if ($product->stock < $newQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => "المخزون المتاح: {$product->stock} قطعة فقط. لديك {$cart->quantity} في السلة",
                ], 422);
            }

            $cart->quantity = $newQuantity;
            $cart->save();
        } else {
            $cart = Cart::create([
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        $cartCount = Cart::where('user_id', $request->user()->id)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المنتج إلى السلة',
            'cart_count' => (int) $cartCount,
        ], 201);
    }

    /**
     * تحديث الكمية
     */
    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح',
            ], 403);
        }

        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart->load('product');

        if (!$cart->product || $cart->product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => $cart->product && $cart->product->stock > 0
                    ? "المخزون المتاح: {$cart->product->stock} قطعة فقط"
                    : 'المنتج غير متوفر حالياً',
            ], 422);
        }

        $cart->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث السلة',
            'cart' => new CartResource($cart->load('product.brand')),
        ]);
    }

    /**
     * حذف من السلة
     */
    public function destroy(Request $request, Cart $cart)
    {
        if ($cart->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح',
            ], 403);
        }

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المنتج من السلة',
        ]);
    }
}
