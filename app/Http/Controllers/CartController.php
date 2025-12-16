<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())
            ->with(['product.brand'])
            ->get();
        
        // إزالة المنتجات غير المتوفرة أو التي نفد مخزونها
        $carts = $carts->filter(function ($cart) {
            if (!$cart->product || $cart->product->stock <= 0) {
                $cart->delete();
                return false;
            }
            // تحديث الكمية إذا كانت أكبر من المخزون
            if ($cart->quantity > $cart->product->stock) {
                $cart->quantity = $cart->product->stock;
                $cart->save();
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
            'quantity' => 'nullable|integer|min:1'
        ]);

        $quantity = $request->input('quantity', 1);

        // فحص المخزون
        if ($product->stock < $quantity) {
            $message = $product->stock > 0 
                ? "المخزون المتاح: {$product->stock} قطعة فقط" 
                : "المنتج غير متوفر حالياً";
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }
            
            return redirect()->back()->with('error', $message);
        }

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {
            $newQuantity = $cart->quantity + $quantity;
            
            // فحص المخزون بعد الجمع
            if ($product->stock < $newQuantity) {
                $message = "المخزون المتاح: {$product->stock} قطعة فقط. لديك {$cart->quantity} في السلة";
                
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                
                return redirect()->back()->with('error', $message);
            }
            
            $cart->quantity = $newQuantity;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
        }

        // عداد السلة
        $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');

        // دعم AJAX
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
        // التأكد من أن المستخدم يملك هذا الـ cart
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا العنصر');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // إعادة تحميل المنتج للتأكد من أحدث بيانات المخزون
        $cart->load('product');

        // فحص المخزون
        if (!$cart->product || $cart->product->stock < $request->quantity) {
            $message = $cart->product && $cart->product->stock > 0 
                ? "المخزون المتاح: {$cart->product->stock} قطعة فقط" 
                : "المنتج غير متوفر حالياً";
            
            return redirect()->back()->with('error', $message);
        }

        $cart->update([
            'quantity' => $request->quantity
        ]);

        return redirect()->back()->with('success', 'تم تحديث السلة');
    }

    public function destroy(Cart $cart)
    {
        // التأكد من أن المستخدم يملك هذا الـ cart
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'ليس لديك صلاحية لحذف هذا العنصر');
        }

        $cart->delete();
        return redirect()->back()->with('success', 'تم حذف المنتج من السلة');
    }
}
