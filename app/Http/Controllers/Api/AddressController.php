<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * عناويني
     */
    public function index(Request $request)
    {
        $addresses = Address::where('user_id', $request->user()->id)
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'addresses' => AddressResource::collection($addresses),
        ]);
    }

    /**
     * إضافة عنوان
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'street' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'area' => ['required', 'string', 'max:100'],
            'building_number' => ['nullable', 'string', 'max:50'],
            'floor' => ['nullable', 'string', 'max:20'],
            'apartment' => ['nullable', 'string', 'max:20'],
            'phone' => ['required', 'string', 'max:20'],
            'additional_info' => ['nullable', 'string', 'max:500'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $validated['user_id'] = $request->user()->id;

        // إذا كان افتراضي، نلغي الافتراضي السابق
        if (!empty($validated['is_default'])) {
            Address::where('user_id', $request->user()->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        // إذا كان أول عنوان، نجعله افتراضياً
        $count = Address::where('user_id', $request->user()->id)->count();
        if ($count === 0) {
            $validated['is_default'] = true;
        }

        $address = Address::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة العنوان بنجاح',
            'address' => new AddressResource($address),
        ], 201);
    }

    /**
     * تعديل عنوان
     */
    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح',
            ], 403);
        }

        $validated = $request->validate([
            'label' => ['sometimes', 'string', 'max:100'],
            'street' => ['sometimes', 'string', 'max:255'],
            'city' => ['sometimes', 'string', 'max:100'],
            'area' => ['sometimes', 'string', 'max:100'],
            'building_number' => ['nullable', 'string', 'max:50'],
            'floor' => ['nullable', 'string', 'max:20'],
            'apartment' => ['nullable', 'string', 'max:20'],
            'phone' => ['sometimes', 'string', 'max:20'],
            'additional_info' => ['nullable', 'string', 'max:500'],
        ]);

        $address->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث العنوان بنجاح',
            'address' => new AddressResource($address->fresh()),
        ]);
    }

    /**
     * حذف عنوان
     */
    public function destroy(Request $request, Address $address)
    {
        if ($address->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح',
            ], 403);
        }

        $address->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف العنوان بنجاح',
        ]);
    }

    /**
     * تعيين عنوان كافتراضي
     */
    public function setDefault(Request $request, Address $address)
    {
        if ($address->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح',
            ], 403);
        }

        // إلغاء الافتراضي السابق
        Address::where('user_id', $request->user()->id)
            ->where('is_default', true)
            ->update(['is_default' => false]);

        $address->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'message' => 'تم تعيين العنوان كافتراضي',
            'address' => new AddressResource($address->fresh()),
        ]);
    }
}
