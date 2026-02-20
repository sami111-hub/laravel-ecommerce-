<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * كل طلباتي
     */
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return OrderResource::collection($orders);
    }

    /**
     * إنشاء طلب جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'coupon_code' => ['nullable', 'string'],
        ]);

        $carts = Cart::where('user_id', $request->user()->id)
            ->with('product')
            ->get();

        if ($carts->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'السلة فارغة',
            ], 422);
        }

        // فحص المخزون
        foreach ($carts as $cart) {
            if (!$cart->product) {
                $cart->delete();
                continue;
            }
            if ($cart->product->stock < $cart->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "المنتج {$cart->product->name} غير متوفر بالكمية المطلوبة. المتوفر: {$cart->product->stock} قطعة",
                ], 422);
            }
        }

        // إعادة فلترة
        $carts = $carts->filter(fn($cart) => $cart->product && $cart->product->stock >= $cart->quantity);

        if ($carts->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد منتجات متوفرة في السلة',
            ], 422);
        }

        $subtotal = $carts->sum(fn($cart) => $cart->product->price * $cart->quantity);
        $discount = 0;
        $coupon = null;

        // تطبيق الكوبون
        if ($request->coupon_code) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();

            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->getDiscountAmount($subtotal);
                $coupon->increment('usage_count');
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'كود الكوبون غير صحيح أو منتهي الصلاحية',
                ], 422);
            }
        }

        $total = $subtotal - $discount;

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'status' => 'pending',
                'total' => $total,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'coupon_code' => $coupon?->code,
                'shipping_address' => $request->shipping_address,
                'phone' => $request->phone,
                'notes' => $request->notes,
                'tracking_code' => 'ORD-' . strtoupper(uniqid()),
            ]);

            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'product_name' => $cart->product->name,
                    'product_price' => $cart->product->price,
                    'quantity' => $cart->quantity,
                    'subtotal' => $cart->product->price * $cart->quantity,
                ]);

                $cart->product->decrement('stock', $cart->quantity);
            }

            Cart::where('user_id', $request->user()->id)->delete();

            DB::commit();

            $order->load('items.product');

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الطلب بنجاح',
                'order' => new OrderResource($order),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الطلب. يرجى المحاولة مرة أخرى',
            ], 500);
        }
    }

    /**
     * تفاصيل طلب
     */
    public function show(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح',
            ], 403);
        }

        $order->load('items.product');

        return response()->json([
            'success' => true,
            'order' => new OrderResource($order),
        ]);
    }

    /**
     * إلغاء طلب
     */
    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح',
            ], 403);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن إلغاء هذا الطلب. يمكن إلغاء الطلبات المعلقة فقط',
            ], 422);
        }

        DB::beginTransaction();
        try {
            // إرجاع المخزون
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            $order->update(['status' => 'cancelled']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إلغاء الطلب بنجاح',
                'order' => new OrderResource($order->fresh('items.product')),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إلغاء الطلب',
            ], 500);
        }
    }
}
