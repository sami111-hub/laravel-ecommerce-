<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingsController extends Controller
{
    /**
     * عرض صفحة إعدادات الموقع
     */
    public function index()
    {
        $settings = SiteSetting::orderBy('group')->orderBy('key')->get();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * تحديث إعدادات الموقع
     */
    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            SiteSetting::set($key, $value);
        }

        return redirect()->back()->with('success', 'تم تحديث الإعدادات بنجاح!');
    }

    /**
     * عرض صفحة إعدادات الشريط الترويجي
     */
    public function promoBar()
    {
        $promoText = SiteSetting::get('promo_bar_text', '');
        $promoEnabled = SiteSetting::get('promo_bar_enabled', '1');
        
        return view('admin.settings.promo-bar', compact('promoText', 'promoEnabled'));
    }

    /**
     * تحديث إعدادات الشريط الترويجي
     */
    public function updatePromoBar(Request $request)
    {
        $request->validate([
            'promo_bar_text' => 'required|string|max:500',
            'promo_bar_enabled' => 'nullable',
        ]);

        // حفظ النص
        SiteSetting::set('promo_bar_text', $request->promo_bar_text, 'textarea', 'promo');
        
        // حفظ حالة التفعيل - التحقق من القيمة بشكل صحيح
        $enabled = $request->input('promo_bar_enabled', '0');
        SiteSetting::set('promo_bar_enabled', $enabled == '1' ? '1' : '0', 'boolean', 'promo');

        // مسح الكاش لضمان تحديث البيانات
        \Illuminate\Support\Facades\Cache::forget('setting_promo_bar_text');
        \Illuminate\Support\Facades\Cache::forget('setting_promo_bar_enabled');

        return redirect()->back()->with('success', 'تم تحديث الشريط الترويجي بنجاح!');
    }

    /**
     * عرض صفحة إعدادات أسعار الصرف
     */
    public function exchangeRates()
    {
        $sarRate = SiteSetting::get('exchange_rate_sar', '3.75');
        $yerRate = SiteSetting::get('exchange_rate_yer', '535');
        
        return view('admin.settings.exchange-rates', compact('sarRate', 'yerRate'));
    }

    /**
     * تحديث أسعار الصرف
     */
    public function updateExchangeRates(Request $request)
    {
        $request->validate([
            'exchange_rate_sar' => 'required|numeric|min:0.01|max:9999',
            'exchange_rate_yer' => 'required|numeric|min:1|max:99999',
        ], [
            'exchange_rate_sar.required' => 'سعر صرف الريال السعودي مطلوب',
            'exchange_rate_sar.numeric' => 'سعر الصرف يجب أن يكون رقم',
            'exchange_rate_yer.required' => 'سعر صرف الريال اليمني مطلوب',
            'exchange_rate_yer.numeric' => 'سعر الصرف يجب أن يكون رقم',
        ]);

        SiteSetting::set('exchange_rate_sar', $request->exchange_rate_sar, 'number', 'currency');
        SiteSetting::set('exchange_rate_yer', $request->exchange_rate_yer, 'number', 'currency');

        \Illuminate\Support\Facades\Cache::forget('setting_exchange_rate_sar');
        \Illuminate\Support\Facades\Cache::forget('setting_exchange_rate_yer');

        return redirect()->back()->with('success', 'تم تحديث أسعار الصرف بنجاح!');
    }

    /**
     * عرض صفحة إدارة السلايدر الرئيسي
     */
    public function heroSlider()
    {
        $slides = [];
        $defaults = [
            1 => [
                'badge' => 'عروض حصرية',
                'title' => 'أحدث الهواتف الذكية',
                'description' => 'خصومات تصل إلى 30% على جميع الهواتف',
                'button_text' => 'تسوق الآن',
                'button_link' => '/products?category=smartphones',
                'image_url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&h=350&fit=crop&q=80',
                'bg_gradient' => 'linear-gradient(135deg, #1a265f 0%, #2d398a 100%)',
            ],
            2 => [
                'badge' => 'جديد',
                'title' => 'كمبيوتر وتابليت بأداء خارق',
                'description' => 'لابتوبات آبل ماك، آيباد، سامسونج، هونر، شاومي وغيرها',
                'button_text' => 'اكتشف المزيد',
                'button_link' => '/products?category=computers-tablets',
                'image_url' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=350&fit=crop&q=80',
                'bg_gradient' => 'linear-gradient(135deg, #1e3a5f 0%, #2980b9 100%)',
            ],
            3 => [
                'badge' => 'الأكثر مبيعاً',
                'title' => 'ساعات ذكية متطورة',
                'description' => 'تتبع صحتك ونشاطك اليومي',
                'button_text' => 'تسوق الآن',
                'button_link' => '/products?category=smartwatches-wearables',
                'image_url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=350&fit=crop&q=80',
                'bg_gradient' => 'linear-gradient(135deg, #6c3483 0%, #9b59b6 100%)',
            ],
        ];

        for ($i = 1; $i <= 3; $i++) {
            $slides[$i] = [
                'badge' => SiteSetting::get("slide{$i}_badge", $defaults[$i]['badge']),
                'title' => SiteSetting::get("slide{$i}_title", $defaults[$i]['title']),
                'description' => SiteSetting::get("slide{$i}_description", $defaults[$i]['description']),
                'button_text' => SiteSetting::get("slide{$i}_button_text", $defaults[$i]['button_text']),
                'button_link' => SiteSetting::get("slide{$i}_button_link", $defaults[$i]['button_link']),
                'image_url' => SiteSetting::get("slide{$i}_image_url", $defaults[$i]['image_url']),
                'bg_gradient' => SiteSetting::get("slide{$i}_bg_gradient", $defaults[$i]['bg_gradient']),
            ];
        }

        return view('admin.settings.hero-slider', compact('slides'));
    }

    /**
     * تحديث بيانات السلايدر الرئيسي
     */
    public function updateHeroSlider(Request $request)
    {
        $request->validate([
            'slide.*.badge' => 'nullable|string|max:50',
            'slide.*.title' => 'required|string|max:100',
            'slide.*.description' => 'nullable|string|max:200',
            'slide.*.button_text' => 'nullable|string|max:50',
            'slide.*.button_link' => 'nullable|string|max:255',
            'slide.*.image_url' => 'nullable|string|max:500',
            'slide.*.bg_gradient' => 'nullable|string|max:255',
        ]);

        $slides = $request->input('slide', []);
        $fields = ['badge', 'title', 'description', 'button_text', 'button_link', 'image_url', 'bg_gradient'];

        for ($i = 1; $i <= 3; $i++) {
            if (isset($slides[$i])) {
                foreach ($fields as $field) {
                    $value = $slides[$i][$field] ?? '';
                    SiteSetting::set("slide{$i}_{$field}", $value, 'text', 'hero_slider');
                    \Illuminate\Support\Facades\Cache::forget("setting_slide{$i}_{$field}");
                }
            }
        }

        return redirect()->back()->with('success', 'تم تحديث السلايدر الرئيسي بنجاح!');
    }
}
