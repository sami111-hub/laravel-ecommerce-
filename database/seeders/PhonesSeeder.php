<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PhoneBrand;
use App\Models\Phone;
use App\Models\PhoneSpec;
use App\Models\PhonePrice;
use Illuminate\Support\Str;

class PhonesSeeder extends Seeder
{
    public function run(): void
    {
        // العلامات التجارية من موقع أبديت (update-aden.com)
        // العلامات المتوفرة: Apple, Anker, LT, Samsung, Redmi
        $brands = [
            ['name' => 'Apple', 'slug' => 'apple', 'logo' => 'brands/apple.png', 'description' => 'أجهزة أبل الأصلية من متجر أبديت'],
            ['name' => 'Samsung', 'slug' => 'samsung', 'logo' => 'brands/samsung.png', 'description' => 'أجهزة سامسونج الأصلية من متجر أبديت'],
            ['name' => 'Redmi', 'slug' => 'redmi', 'logo' => 'brands/redmi.png', 'description' => 'أجهزة ريدمي الأصلية من متجر أبديت'],
            ['name' => 'Anker', 'slug' => 'anker', 'logo' => 'brands/anker.png', 'description' => 'منتجات أنكر الأصلية من متجر أبديت'],
            ['name' => 'Anker SoundCore', 'slug' => 'anker-soundcore', 'logo' => 'brands/anker-soundcore.png', 'description' => 'سماعات Anker SoundCore الأصلية'],
            ['name' => 'ZTE Redmagic', 'slug' => 'zte-redmagic', 'logo' => 'brands/zte-redmagic.png', 'description' => 'هواتف الألعاب ZTE Redmagic'],
            ['name' => 'LT', 'slug' => 'lt', 'logo' => 'brands/lt.png', 'description' => 'منتجات LT من متجر أبديت'],
        ];

        foreach ($brands as $index => $brandData) {
            PhoneBrand::updateOrCreate(
                ['slug' => $brandData['slug']],
                array_merge($brandData, ['order' => $index, 'is_active' => true])
            );
        }

        // الهواتف من موقع أبديت (البيانات الفعلية المتاحة)
        $phones = [
            // Redmi Note 12 - من موقع أبديت (Item Code: 1085)
            [
                'brand_slug' => 'redmi',
                'name' => 'Note 12',
                'full_name' => 'Redmi Note 12 12GB+256GB',
                'chipset' => 'Snapdragon 4 Gen 1',
                'ram' => '12GB',
                'storage' => '256GB',
                'display_size' => 6.67,
                'battery_mah' => 5000,
                'os' => 'Android 13',
                'release_year' => 2023,
                'price' => 320000, // السعر التقريبي بالريال اليمني
                'description' => 'هاتف Redmi Note 12 بذاكرة 12 جيجا رام و 256 جيجا تخزين من متجر أبديت'
            ],
            // Samsung A80 - من موقع أبديت (Item Code: 188, السعر: $400)
            [
                'brand_slug' => 'samsung',
                'name' => 'Galaxy A80',
                'full_name' => 'Samsung Galaxy A80',
                'chipset' => 'Snapdragon 730G',
                'ram' => '8GB',
                'storage' => '128GB',
                'display_size' => 6.7,
                'battery_mah' => 3700,
                'os' => 'Android 11',
                'release_year' => 2019,
                'price' => 400000, // $400 بالدولار حسب موقع أبديت
                'description' => 'هاتف Samsung Galaxy A80 بكاميرا دوارة فريدة من نوعها من متجر أبديت'
            ],
        ];

        foreach ($phones as $phoneData) {
            $brand = PhoneBrand::where('slug', $phoneData['brand_slug'])->first();
            if (!$brand) continue;

            $slug = Str::slug($brand->name . '-' . $phoneData['name']);
            
            $phone = Phone::updateOrCreate(
                ['slug' => $slug],
                [
                    'brand_id' => $brand->id,
                    'name' => $phoneData['full_name'],
                    'chipset' => $phoneData['chipset'],
                    'ram' => $phoneData['ram'],
                    'storage' => $phoneData['storage'],
                    'display_size' => $phoneData['display_size'],
                    'battery_mah' => $phoneData['battery_mah'],
                    'os' => $phoneData['os'],
                    'release_year' => $phoneData['release_year'],
                    'description' => $phoneData['description'],
                    'is_active' => true,
                ]
            );

            // المواصفات
            $specs = [
                ['group' => 'الشاشة', 'key' => 'الحجم', 'value' => $phoneData['display_size'] . ' بوصة'],
                ['group' => 'الشاشة', 'key' => 'النوع', 'value' => 'AMOLED'],
                ['group' => 'المعالج', 'key' => 'المعالج', 'value' => $phoneData['chipset']],
                ['group' => 'الذاكرة', 'key' => 'RAM', 'value' => $phoneData['ram']],
                ['group' => 'الذاكرة', 'key' => 'التخزين', 'value' => $phoneData['storage']],
                ['group' => 'البطارية', 'key' => 'السعة', 'value' => $phoneData['battery_mah'] . ' mAh'],
                ['group' => 'النظام', 'key' => 'نظام التشغيل', 'value' => $phoneData['os']],
            ];

            foreach ($specs as $index => $spec) {
                PhoneSpec::updateOrCreate(
                    [
                        'phone_id' => $phone->id,
                        'group' => $spec['group'],
                        'key' => $spec['key']
                    ],
                    [
                        'value' => $spec['value'],
                        'order' => $index
                    ]
                );
            }

            // السعر (السعر الفعلي من موقع أبديت)
            PhonePrice::updateOrCreate(
                [
                    'phone_id' => $phone->id,
                    'region' => 'Aden',
                    'effective_date' => now()->toDateString()
                ],
                [
                    'currency' => 'USD', // الموقع يستخدم الدولار
                    'price' => $phoneData['price'],
                    'source' => 'أبديت تكنولوجي - update-aden.com',
                    'is_current' => true
                ]
            );
        }

        $this->command->info('✅ تم استيراد ' . count($phones) . ' هاتف من موقع أبديت بنجاح!');
        $this->command->info('🌐 المصدر: update-aden.com');
        $this->command->info('📍 الموقع: اليمن - عدن');
    }
}
