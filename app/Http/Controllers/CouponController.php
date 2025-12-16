<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0'
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'كود الخصم غير موجود'
            ]);
        }

        if (!$coupon->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'كود الخصم غير صالح أو منتهي الصلاحية'
            ]);
        }

        if ($coupon->min_order && $request->amount < $coupon->min_order) {
            return response()->json([
                'success' => false,
                'message' => 'الحد الأدنى للطلب: $' . number_format($coupon->min_order, 2)
            ]);
        }

        $discount = $coupon->getDiscountAmount($request->amount);

        return response()->json([
            'success' => true,
            'message' => 'تم تطبيق الكوبون بنجاح',
            'discount' => round($discount, 2),
            'type' => $coupon->type,
            'value' => $coupon->value
        ]);
    }
}


