<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use App\Models\PhoneBrand;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
    /**
     * عرض قائمة الهواتف
     */
    public function index(Request $request)
    {
        $query = Phone::with(['brand', 'specs', 'prices']);

        // البحث
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('brand', function($brandQuery) use ($search) {
                      $brandQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // فلترة حسب البراند
        if ($request->has('brand') && $request->brand) {
            $query->where('brand_id', $request->brand);
        }

        // فلترة حسب السعر
        if ($request->has('min_price') && $request->min_price) {
            $query->whereHas('prices', function($priceQuery) use ($request) {
                $priceQuery->where('price', '>=', $request->min_price);
            });
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->whereHas('prices', function($priceQuery) use ($request) {
                $priceQuery->where('price', '<=', $request->max_price);
            });
        }

        // الترتيب
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        if ($sortBy === 'price') {
            // ترتيب حسب السعر يتطلب join
            $query->join('phone_prices', 'phones.id', '=', 'phone_prices.phone_id')
                  ->select('phones.*')
                  ->orderBy('phone_prices.price', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $phones = $query->paginate(12);
        $brands = PhoneBrand::withCount('phones')
            ->orderBy('name')
            ->get();

        return view('phones.index', compact('phones', 'brands'));
    }

    /**
     * عرض تفاصيل هاتف محدد
     */
    public function show($slug)
    {
        $phone = Phone::with(['brand', 'specs', 'prices'])
            ->where('slug', $slug)
            ->firstOrFail();

        // هواتف مشابهة من نفس البراند
        $relatedPhones = Phone::with(['brand', 'prices'])
            ->where('brand_id', $phone->brand_id)
            ->where('id', '!=', $phone->id)
            ->take(4)
            ->get();

        return view('phones.show', compact('phone', 'relatedPhones'));
    }

    /**
     * البحث في الهواتف (AJAX)
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');
        
        $phones = Phone::with(['brand', 'prices'])
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('brand', function($brandQuery) use ($search) {
                          $brandQuery->where('name', 'like', "%{$search}%");
                      });
            })
            ->take(10)
            ->get();

        return response()->json($phones);
    }

    /**
     * المقارنة بين الهواتف
     */
    public function compare(Request $request)
    {
        $phoneIds = $request->get('phones', []);
        
        if (empty($phoneIds) || count($phoneIds) > 4) {
            return redirect()->route('phones.index')
                ->with('error', 'يمكنك مقارنة من 2 إلى 4 هواتف فقط');
        }

        $phones = Phone::with(['brand', 'specs', 'prices'])
            ->whereIn('id', $phoneIds)
            ->get();

        if ($phones->count() < 2) {
            return redirect()->route('phones.index')
                ->with('error', 'يجب اختيار هاتفين على الأقل للمقارنة');
        }

        return view('phones.compare', compact('phones'));
    }

    /**
     * عرض الهواتف حسب البراند
     */
    public function brand($slug)
    {
        $brand = PhoneBrand::where('slug', $slug)
            ->firstOrFail();

        $phones = Phone::with(['brand', 'prices'])
            ->where('brand_id', $brand->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('phones.brand', compact('brand', 'phones'));
    }

    /**
     * عرض أحدث الهواتف
     */
    public function latest()
    {
        $phones = Phone::with(['brand', 'prices'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $title = 'أحدث الهواتف';

        return view('phones.latest', compact('phones', 'title'));
    }

    /**
     * عرض الهواتف الأكثر مبيعاً
     */
    public function popular()
    {
        $phones = Phone::with(['brand', 'prices'])
            ->orderBy('views', 'desc')
            ->paginate(12);

        $title = 'الهواتف الأكثر شعبية';

        return view('phones.popular', compact('phones', 'title'));
    }
}
