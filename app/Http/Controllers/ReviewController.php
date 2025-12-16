<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Product;
use App\Models\Order;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Product $product)
    {
        // التحقق من أن المستخدم قد اشترى المنتج
        $hasPurchased = Order::where('user_id', Auth::id())
            ->whereHas('items', function($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->where('status', 'completed')
            ->exists();

        // السماح بالمراجعة حتى لو لم يشتري (يمكن تغيير هذا)
        // if (!$hasPurchased) {
        //     return redirect()->back()->with('error', 'يجب شراء المنتج أولاً لتتمكن من تقييمه');
        // }

        // التحقق من أن المستخدم لم يقيم المنتج من قبل
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'لقد قمت بتقييم هذا المنتج من قبل');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'is_approved' => true // يمكن تغييرها لتحتاج موافقة
        ]);

        return redirect()->back()->with('success', 'شكراً لك! تم إضافة تقييمك بنجاح');
    }

    public function update(Request $request, Review $review)
    {
        // التأكد من أن المستخدم يملك هذا التقييم
        if ($review->user_id !== Auth::id()) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا التقييم');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $review->update($validated);

        return redirect()->back()->with('success', 'تم تحديث تقييمك بنجاح');
    }

    public function destroy(Review $review)
    {
        // التأكد من أن المستخدم يملك هذا التقييم أو هو مدير
        if ($review->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'ليس لديك صلاحية لحذف هذا التقييم');
        }

        $review->delete();

        return redirect()->back()->with('success', 'تم حذف التقييم بنجاح');
    }
}

