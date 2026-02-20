<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class FlashDealController extends Controller
{
    /**
     * عرض صفحة إدارة عروض اليوم
     */
    public function index()
    {
        $flashProducts = Product::where('is_flash_deal', true)
            ->with(['category', 'brand'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $settings = [
            'title' => SiteSetting::get('flash_deals_title', 'عروض اليوم'),
            'is_active' => SiteSetting::get('flash_deals_active', '1'),
            'end_date' => SiteSetting::get('flash_deals_end_date', now()->endOfDay()->format('Y-m-d')),
            'end_time' => SiteSetting::get('flash_deals_end_time', '23:59'),
            'max_products' => SiteSetting::get('flash_deals_max_products', '6'),
        ];

        // تحميل جميع المنتجات غير المضافة للعرض (للبحث في الجافاسكربت)
        $availableProducts = Product::where('is_flash_deal', false)
            ->with(['brand:id,name'])
            ->get(['id', 'name', 'price', 'image', 'sku', 'brand_id']);

        return view('admin.flash-deals.index', compact('flashProducts', 'settings', 'availableProducts'));
    }

    /**
     * تحديث إعدادات عروض اليوم
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'is_active' => 'nullable',
            'end_date' => 'required|date',
            'end_time' => 'required',
            'max_products' => 'required|integer|min:1|max:20',
        ]);

        SiteSetting::set('flash_deals_title', $request->title, 'text', 'flash_deals');
        SiteSetting::set('flash_deals_active', $request->has('is_active') ? '1' : '0', 'text', 'flash_deals');
        SiteSetting::set('flash_deals_end_date', $request->end_date, 'text', 'flash_deals');
        SiteSetting::set('flash_deals_end_time', $request->end_time, 'text', 'flash_deals');
        SiteSetting::set('flash_deals_max_products', $request->max_products, 'number', 'flash_deals');

        // تحديث وقت الانتهاء لجميع منتجات العرض
        $endsAt = $request->end_date . ' ' . $request->end_time . ':00';
        Product::where('is_flash_deal', true)->update(['flash_deal_ends_at' => $endsAt]);

        return redirect()->back()->with('success', 'تم تحديث إعدادات عروض اليوم بنجاح!');
    }

    /**
     * البحث عن منتجات لإضافتها
     */
    public function searchProducts(Request $request)
    {
        try {
            $query = $request->get('q', '');

            if (empty($query)) {
                return response()->json([]);
            }

            $products = Product::where('is_flash_deal', false)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('sku', 'like', "%{$query}%");
                })
                ->with(['brand:id,name'])
                ->take(10)
                ->get(['id', 'name', 'price', 'image', 'sku', 'brand_id']);

            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * إضافة منتج لعروض اليوم
     */
    public function addProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount' => 'required|integer|min:1|max:99',
        ]);

        $endDate = SiteSetting::get('flash_deals_end_date', now()->endOfDay()->format('Y-m-d'));
        $endTime = SiteSetting::get('flash_deals_end_time', '23:59');
        $endsAt = $endDate . ' ' . $endTime . ':00';

        $product = Product::findOrFail($request->product_id);
        $flashPrice = round($product->price * (1 - $request->discount / 100), 2);

        $product->update([
            'is_flash_deal' => true,
            'flash_deal_discount' => $request->discount,
            'flash_deal_price' => $flashPrice,
            'flash_deal_ends_at' => $endsAt,
        ]);

        return redirect()->back()->with('success', "تم إضافة \"{$product->name}\" لعروض اليوم بخصم {$request->discount}%");
    }

    /**
     * تحديث خصم منتج
     */
    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'discount' => 'required|integer|min:1|max:99',
        ]);

        $flashPrice = round($product->price * (1 - $request->discount / 100), 2);

        $product->update([
            'flash_deal_discount' => $request->discount,
            'flash_deal_price' => $flashPrice,
        ]);

        return redirect()->back()->with('success', "تم تحديث خصم \"{$product->name}\" إلى {$request->discount}%");
    }

    /**
     * إزالة منتج من عروض اليوم
     */
    public function removeProduct(Product $product)
    {
        $product->update([
            'is_flash_deal' => false,
            'flash_deal_discount' => null,
            'flash_deal_price' => null,
            'flash_deal_ends_at' => null,
        ]);

        return redirect()->back()->with('success', "تم إزالة \"{$product->name}\" من عروض اليوم");
    }
}
