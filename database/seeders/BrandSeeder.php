<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // العلامات التجارية من موقع أبديت تكنولوجي (update-aden.com)
        // تم استخراجها من صفحة Shop by Category
        $brands = [
            // العلامات الرئيسية
            ['name' => 'Apple', 'slug' => 'apple', 'description' => 'منتجات Apple الأصلية - iPhone, iPad, MacBook, Apple Watch', 'is_active' => true],
            ['name' => 'Samsung', 'slug' => 'samsung', 'description' => 'هواتف Samsung Galaxy وأجهزة إلكترونية', 'is_active' => true],
            ['name' => 'Xiaomi', 'slug' => 'xiaomi', 'description' => 'هواتف وإكسسوارات Xiaomi', 'is_active' => true],
            ['name' => 'Redmi', 'slug' => 'redmi', 'description' => 'هواتف Redmi بأفضل الأسعار', 'is_active' => true],
            ['name' => 'Oppo', 'slug' => 'oppo', 'description' => 'هواتف Oppo الذكية', 'is_active' => true],
            ['name' => 'Vivo', 'slug' => 'vivo', 'description' => 'هواتف Vivo', 'is_active' => true],
            ['name' => 'Honor', 'slug' => 'honor', 'description' => 'هواتف Honor', 'is_active' => true],
            ['name' => 'Huawei', 'slug' => 'huawei', 'description' => 'أجهزة Huawei', 'is_active' => true],
            ['name' => 'Nokia', 'slug' => 'nokia', 'description' => 'هواتف Nokia', 'is_active' => true],
            ['name' => 'Oneplus', 'slug' => 'oneplus', 'description' => 'هواتف OnePlus', 'is_active' => true],
            ['name' => 'Sony', 'slug' => 'sony', 'description' => 'منتجات Sony الإلكترونية', 'is_active' => true],
            
            // علامات الإكسسوارات
            ['name' => 'Anker', 'slug' => 'anker', 'description' => 'شواحن وبطاريات Anker الأصلية', 'is_active' => true],
            ['name' => 'JBL', 'slug' => 'jbl', 'description' => 'سماعات JBL الأصلية', 'is_active' => true],
            ['name' => 'Beats', 'slug' => 'beats', 'description' => 'سماعات Beats by Dr. Dre', 'is_active' => true],
            ['name' => 'Green lion', 'slug' => 'green-lion', 'description' => 'إكسسوارات Green Lion', 'is_active' => true],
            ['name' => 'BoroFone', 'slug' => 'borofone', 'description' => 'إكسسوارات BoroFone', 'is_active' => true],
            ['name' => 'Porodo', 'slug' => 'porodo', 'description' => 'إكسسوارات Porodo', 'is_active' => true],
            ['name' => 'Powerology', 'slug' => 'powerology', 'description' => 'بطاريات وشواحن Powerology', 'is_active' => true],
            ['name' => 'Levelo', 'slug' => 'levelo', 'description' => 'إكسسوارات Levelo', 'is_active' => true],
            
            // علامات الحواسيب
            ['name' => 'Dell', 'slug' => 'dell', 'description' => 'أجهزة Dell للكمبيوتر والابتوب', 'is_active' => true],
            ['name' => 'HP', 'slug' => 'hp', 'description' => 'أجهزة HP وطابعات', 'is_active' => true],
            ['name' => 'Lenovo', 'slug' => 'lenovo', 'description' => 'لابتوبات Lenovo', 'is_active' => true],
            ['name' => 'Asus', 'slug' => 'asus', 'description' => 'أجهزة Asus', 'is_active' => true],
            ['name' => 'Microsoft', 'slug' => 'microsoft', 'description' => 'منتجات Microsoft', 'is_active' => true],
            
            // علامات الطابعات
            ['name' => 'Epson', 'slug' => 'epson', 'description' => 'طابعات Epson', 'is_active' => true],
            ['name' => 'Canon', 'slug' => 'canon', 'description' => 'طابعات وكاميرات Canon', 'is_active' => true],
            
            // علامات أخرى
            ['name' => 'LT', 'slug' => 'lt', 'description' => 'منتجات LT', 'is_active' => true],
            ['name' => 'Google', 'slug' => 'google', 'description' => 'منتجات Google Pixel', 'is_active' => true],
            ['name' => 'Go pro', 'slug' => 'gopro', 'description' => 'كاميرات GoPro', 'is_active' => true],
            ['name' => 'Osmo', 'slug' => 'osmo', 'description' => 'منتجات Osmo', 'is_active' => true],
            ['name' => 'Rode', 'slug' => 'rode', 'description' => 'ميكروفونات Rode', 'is_active' => true],
            ['name' => 'Sandisk', 'slug' => 'sandisk', 'description' => 'ذواكر Sandisk', 'is_active' => true],
            ['name' => 'TP-Link', 'slug' => 'tp-link', 'description' => 'أجهزة شبكات TP-Link', 'is_active' => true],
            ['name' => 'D-Link', 'slug' => 'd-link', 'description' => 'أجهزة شبكات D-Link', 'is_active' => true],
            ['name' => 'Uniview', 'slug' => 'uniview', 'description' => 'كاميرات مراقبة Uniview', 'is_active' => true],
            ['name' => 'LG', 'slug' => 'lg', 'description' => 'منتجات LG', 'is_active' => true],
            ['name' => 'Bison', 'slug' => 'bison', 'description' => 'هواتف Bison المتينة', 'is_active' => true],
            ['name' => 'Meizu', 'slug' => 'meizu', 'description' => 'هواتف Meizu', 'is_active' => true],
            ['name' => 'Cool pad', 'slug' => 'coolpad', 'description' => 'هواتف Coolpad', 'is_active' => true],
            ['name' => 'Black view', 'slug' => 'blackview', 'description' => 'هواتف Blackview', 'is_active' => true],
            
            // علامات محلية/عربية
            ['name' => 'الفاتن', 'slug' => 'alfaten', 'description' => 'منتجات الفاتن', 'is_active' => true],
            ['name' => 'البراق', 'slug' => 'alboraq', 'description' => 'منتجات البراق', 'is_active' => true],
            ['name' => 'بساط الرياح', 'slug' => 'besat-alriyah', 'description' => 'منتجات بساط الرياح', 'is_active' => true],
            ['name' => 'عابر القارات', 'slug' => 'aber-alqarat', 'description' => 'منتجات عابر القارات', 'is_active' => true],
            ['name' => 'كيو سيرا', 'slug' => 'qsera', 'description' => 'منتجات كيو سيرا', 'is_active' => true],
            
            ['name' => 'Other', 'slug' => 'other', 'description' => 'علامات تجارية أخرى', 'is_active' => true],

            // علامات مضافة حديثاً
            ['name' => 'Anker SoundCore', 'slug' => 'anker-soundcore', 'description' => 'سماعات وإكسسوارات صوتية Anker SoundCore الأصلية', 'is_active' => true],
            ['name' => 'ZTE Redmagic', 'slug' => 'zte-redmagic', 'description' => 'هواتف الألعاب ZTE Redmagic بأداء خارق', 'is_active' => true],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['slug' => $brand['slug']],
                $brand
            );
        }

        $this->command->info('✅ تم استيراد ' . count($brands) . ' علامة تجارية من موقع أبديت');
    }
}
