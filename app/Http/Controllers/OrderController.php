<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Product;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة');
        }

        // فحص المخزون وإزالة المنتجات غير المتوفرة
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

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'لا توجد منتجات متوفرة في السلة');
        }

        $subtotal = $carts->sum(function ($cart) {
            return $cart->product->price * $cart->quantity;
        });

        return view('orders.create', compact('carts', 'subtotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'phone' => 'required|string',
            'notes' => 'nullable|string',
            'coupon_code' => 'nullable|string'
        ]);

        $carts = Cart::where('user_id', Auth::id())->with('product')->get();

        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'السلة فارغة');
        }

        // فحص المخزون قبل إنشاء الطلب
        foreach ($carts as $cart) {
            // إعادة تحميل المنتج للتأكد من أحدث بيانات المخزون
            $cart->load('product');
            
            if (!$cart->product) {
                $cart->delete();
                continue;
            }
            
            if ($cart->product->stock < $cart->quantity) {
                $productName = $cart->product->name ?? 'منتج غير معروف';
                return redirect()->back()->with('error', "المنتج {$productName} غير متوفر بالكمية المطلوبة. المتوفر: {$cart->product->stock} قطعة");
            }
        }
        
        // إعادة فحص السلة بعد التنظيف
        $carts = $carts->filter(function ($cart) {
            return $cart->product && $cart->product->stock >= $cart->quantity;
        });
        
        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'لا توجد منتجات متوفرة في السلة');
        }

        $subtotal = $carts->sum(function ($cart) {
            return $cart->product->price * $cart->quantity;
        });

        $discount = 0;
        $coupon = null;
        
        // تطبيق الكوبون إذا كان موجوداً
        if ($request->coupon_code) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();
            
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->getDiscountAmount($subtotal);
                $coupon->increment('usage_count');
            } else {
                return redirect()->back()->with('error', 'كود الكوبون غير صحيح أو منتهي الصلاحية');
            }
        }

        $total = $subtotal - $discount;

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
                'total' => $total,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'coupon_code' => $coupon ? $coupon->code : null,
                'shipping_address' => $request->shipping_address,
                'phone' => $request->phone,
                'notes' => $request->notes,
                'tracking_code' => 'ORD-' . strtoupper(uniqid())
            ]);

            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'product_name' => $cart->product->name,
                    'product_price' => $cart->product->price,
                    'quantity' => $cart->quantity,
                    'subtotal' => $cart->product->price * $cart->quantity
                ]);

                // تقليل المخزون
                $cart->product->decrement('stock', $cart->quantity);
            }

            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('orders.show', $order)->with('success', 'تم إنشاء الطلب بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الطلب. يرجى المحاولة مرة أخرى');
        }
    }

    public function show(Order $order)
    {
        // التأكد من أن المستخدم يملك هذا الطلب
        if ($order->user_id !== Auth::id()) {
            abort(403, 'ليس لديك صلاحية لعرض هذا الطلب');
        }

        $order->load('items.product');
        return view('orders.show', compact('order'));
    }
}
