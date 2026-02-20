<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PhoneResource;
use App\Http\Resources\PhoneBrandResource;
use App\Models\Phone;
use App\Models\PhoneBrand;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
    /**
     * قائمة الهواتف
     */
    public function index(Request $request)
    {
        $query = Phone::with(['brand', 'specs', 'prices'])->active();

        // بحث
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // فلتر حسب البراند
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // فلتر حسب السعر
        if ($request->filled('min_price')) {
            $query->whereHas('prices', function ($q) use ($request) {
                $q->where('is_current', true)->where('price', '>=', $request->min_price);
            });
        }
        if ($request->filled('max_price')) {
            $query->whereHas('prices', function ($q) use ($request) {
                $q->where('is_current', true)->where('price', '<=', $request->max_price);
            });
        }

        // الترتيب
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $perPage = min($request->get('per_page', 12), 50);
        $phones = $query->paginate($perPage);

        return PhoneResource::collection($phones);
    }

    /**
     * تفاصيل هاتف
     */
    public function show($slug)
    {
        $phone = Phone::with(['brand', 'specs', 'prices'])
            ->where('slug', $slug)
            ->firstOrFail();

        // زيادة المشاهدات
        $phone->increment('views');

        // هواتف مشابهة
        $related = Phone::with(['brand', 'prices'])
            ->where('brand_id', $phone->brand_id)
            ->where('id', '!=', $phone->id)
            ->active()
            ->take(4)
            ->get();

        return response()->json([
            'success' => true,
            'phone' => new PhoneResource($phone),
            'related' => PhoneResource::collection($related),
        ]);
    }

    /**
     * بحث في الهواتف
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json(['success' => true, 'phones' => []]);
        }

        $phones = Phone::with(['brand', 'prices'])
            ->active()
            ->search($query)
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'phones' => PhoneResource::collection($phones),
        ]);
    }

    /**
     * أحدث الهواتف
     */
    public function latest()
    {
        $phones = Phone::with(['brand', 'prices'])
            ->active()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'phones' => PhoneResource::collection($phones),
        ]);
    }

    /**
     * الأكثر شعبية
     */
    public function popular()
    {
        $phones = Phone::with(['brand', 'prices'])
            ->active()
            ->orderBy('views', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'phones' => PhoneResource::collection($phones),
        ]);
    }

    /**
     * مقارنة هواتف
     */
    public function compare(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'string'],
        ]);

        $ids = explode(',', $request->ids);

        if (count($ids) < 2 || count($ids) > 4) {
            return response()->json([
                'success' => false,
                'message' => 'يمكنك مقارنة من 2 إلى 4 هواتف فقط',
            ], 422);
        }

        $phones = Phone::with(['brand', 'specs', 'prices'])
            ->whereIn('id', $ids)
            ->get();

        if ($phones->count() < 2) {
            return response()->json([
                'success' => false,
                'message' => 'يجب اختيار هاتفين على الأقل للمقارنة',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'phones' => PhoneResource::collection($phones),
        ]);
    }

    /**
     * العلامات التجارية
     */
    public function brands()
    {
        $brands = PhoneBrand::where('is_active', true)
            ->withCount('phones')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'brands' => PhoneBrandResource::collection($brands),
        ]);
    }

    /**
     * هواتف علامة تجارية معينة
     */
    public function brandPhones(Request $request, $slug)
    {
        $brand = PhoneBrand::where('slug', $slug)->firstOrFail();

        $phones = Phone::with(['brand', 'prices'])
            ->where('brand_id', $brand->id)
            ->active()
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return response()->json([
            'success' => true,
            'brand' => new PhoneBrandResource($brand),
            'phones' => PhoneResource::collection($phones),
            'meta' => [
                'current_page' => $phones->currentPage(),
                'last_page' => $phones->lastPage(),
                'total' => $phones->total(),
            ],
        ]);
    }
}
