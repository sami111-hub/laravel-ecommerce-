<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * تقييمات منتج
     */
    public function index(Product $product)
    {
        $reviews = $product->reviews()
            ->where('is_approved', true)
            ->with('user')
            ->latest()
            ->paginate(15);

        $stats = [
            'average' => round($product->reviews()->where('is_approved', true)->avg('rating') ?? 0, 1),
            'total' => $product->reviews()->where('is_approved', true)->count(),
            'breakdown' => [],
        ];

        for ($i = 5; $i >= 1; $i--) {
            $stats['breakdown'][$i] = $product->reviews()
                ->where('is_approved', true)
                ->where('rating', $i)
                ->count();
        }

        return response()->json([
            'success' => true,
            'reviews' => ReviewResource::collection($reviews),
            'stats' => $stats,
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'total' => $reviews->total(),
            ],
        ]);
    }

    /**
     * إضافة تقييم
     */
    public function store(Request $request, Product $product)
    {
        // التحقق من عدم التقييم مسبقاً
        $existing = Review::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'لقد قمت بتقييم هذا المنتج من قبل',
            ], 422);
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $review = Review::create([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'is_approved' => true,
        ]);

        $review->load('user');

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة تقييمك بنجاح',
            'review' => new ReviewResource($review),
        ], 201);
    }

    /**
     * تعديل تقييم
     */
    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح',
            ], 403);
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $review->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث تقييمك بنجاح',
            'review' => new ReviewResource($review->load('user')),
        ]);
    }

    /**
     * حذف تقييم
     */
    public function destroy(Request $request, Review $review)
    {
        if ($review->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح',
            ], 403);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف التقييم بنجاح',
        ]);
    }
}
