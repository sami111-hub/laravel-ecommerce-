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
            'promo_bar_enabled' => 'nullable|boolean',
        ]);

        SiteSetting::set('promo_bar_text', $request->promo_bar_text, 'textarea', 'promo');
        SiteSetting::set('promo_bar_enabled', $request->has('promo_bar_enabled') ? '1' : '0', 'boolean', 'promo');

        return redirect()->back()->with('success', 'تم تحديث الشريط الترويجي بنجاح!');
    }
}
