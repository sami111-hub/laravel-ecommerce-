<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use App\Models\Coupon;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * العروض المتاحة
     */
    public function index()
    {
        $activeOffers = Offer::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('discount_percentage', 'desc')
            ->get();

        $upcomingOffers = Offer::where('is_active', true)
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'active_offers' => OfferResource::collection($activeOffers),
            'upcoming_offers' => OfferResource::collection($upcomingOffers),
        ]);
    }

    /**
     * التحقق من كوبون
     */
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'كود الخصم غير موجود',
            ], 404);
        }

        if (!$coupon->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'كود الخصم غير صالح أو منتهي الصلاحية',
            ], 422);
        }

        if ($coupon->min_order && $request->amount < $coupon->min_order) {
            return response()->json([
                'success' => false,
                'message' => 'الحد الأدنى للطلب: $' . number_format($coupon->min_order, 2),
            ], 422);
        }

        $discount = $coupon->getDiscountAmount($request->amount);

        return response()->json([
            'success' => true,
            'message' => 'تم التحقق من الكوبون بنجاح',
            'discount' => round($discount, 2),
            'type' => $coupon->type,
            'value' => $coupon->value,
            'new_total' => round($request->amount - $discount, 2),
        ]);
    }
}
