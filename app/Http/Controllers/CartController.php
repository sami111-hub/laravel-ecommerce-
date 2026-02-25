<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())
            ->with(['product.brand', 'variant'])
            ->get();
        
        // إزالة المنتجات غير المتوفرة
        $carts = $carts->filter(function ($cart) {
            if (!$cart->product) {
                $cart->delete();
                return false;
            }
            
            // إذا المنتج مرتبط بموديل
            if ($cart->variant_id) {
                // إذا الموديل محذوف أو نفد مخزونه
                if (!$cart->variant || $cart->variant->stock <= 0 || !$cart->variant->is_active) {
                    $cart->delete();
                    return false;
                }
                // تحديث الكمية إذا أكبر من مخزون الموديل
                if ($cart->quantity > $cart->variant->stock) {
                    $cart->quantity = $cart->variant->stock;
                    $cart->save();
                }
            } else {
                // منتج عادي بدون موديلات
                if ($cart->product->stock <= 0) {
                    $cart->delete();
                    return false;
                }
                if ($cart->quantity > $cart->product->stock) {
                    $cart->quantity = $cart->product->stock;
                    $cart->save();
                }
            }
            return true;
        });
        
        $subtotal = $carts->sum(function ($cart) {
            return $cart->product->price * $cart->quantity;
        });

        return view('cart.index', compact('carts', 'subtotal'));
    }

    public function getCount()
    {
        $count = Cart::where('user_id', Auth::id())->sum('quantity');
        return response()->json(['count' => $count]);
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
        ]);

        $quantity = $request->input('quantity', 1);
        $variantId = $request->input('variant_id');

        // تحقق: إذا المنتج فيه موديلات، يجب اختيار موديل
        $hasVariants = $product->variants()->where('is_active', true)->where('stock', '>', 0)->exists();
        if ($hasVariants && !$variantId) {
            $message = 'يرجى اختيار الموديل المطلوب أولاً';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return redirect()->back()->with('error', $message);
        }

        // فحص المخزون
        if ($variantId) {
            $variant = ProductVariant::where('id', $variantId)
                ->where('product_id', $product->id)
                ->where('is_active', true)
                ->first();
            
            if (!$variant || $variant->stock < $quantity) {
                $available = $variant ? $variant->stock : 0;
                $message = $available > 0
                    ? "المخزون المتاح لهذا الموديل: {$available} قطعة فقط"
                    : "هذا الموديل غير متوفر حالياً";
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return redirect()->back()->with('error', $message);
            }
            $availableStock = $variant->stock;
        } else {
            if ($product->stock < $quantity) {
                $message = $product->stock > 0
                    ? "المخزون المتاح: {$product->stock} قطعة فقط"
                    : "المنتج غير متوفر حالياً";
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return redirect()->back()->with('error', $message);
            }
            $availableStock = $product->stock;
        }

        // البحث عن عنصر موجود في السلة (نفس المنتج + نفس الموديل)
        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('variant_id', $variantId)
            ->first();

        if ($cart) {
            $newQuantity = $cart->quantity + $quantity;
            
            if ($availableStock < $newQuantity) {
                $message = "المخزون المتاح: {$availableStock} قطعة فقط. لديك {$cart->quantity} في السلة";
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return redirect()->back()->with('error', $message);
            }
            
            $cart->quantity = $newQuantity;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'variant_id' => $variantId,
                'quantity' => $quantity,
            ]);
        }

        $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المنتج إلى السلة',
                'cartCount' => $cartCount
            ]);
        }

        return redirect()->back()->with('success', 'تم إضافة المنتج إلى السلة');
    }

    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا العنصر');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart->load(['product', 'variant']);

        // فحص المخزون
        if ($cart->variant_id && $cart->variant) {
            $availableStock = $cart->variant->stock;
        } elseif ($cart->product) {
            $availableStock = $cart->product->stock;
        } else {
            $cart->delete();
            return redirect()->back()->with('error', 'المنتج غير متوفر');
        }

        if ($request->quantity > $availableStock) {
            $message = "المخزون المتاح: {$availableStock} قطعة فقط";
            return redirect()->back()->with('error', $message);
        }

        $cart->update(['quantity' => $request->quantity]);
        return redirect()->back()->with('success', 'تم تحديث السلة');
    }

    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'ليس لديك صلاحية لحذف هذا العنصر');
        }

        $cart->delete();
        return redirect()->back()->with('success', 'تم حذف المنتج من السلة');
    }
}
