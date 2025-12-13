<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;

class UpdateAdenProductsSeeder extends Seeder
{
    public function run(): void
    {
        // المنتجات من موقع أبديت تكنولوجي (update-aden.com)
        $products = [
            // الهواتف الذكية (من صفحة all-products)
            [
                'name' => 'iPhone مستخدم',
                'description' => 'ايفون مستخدم بحالة ممتازة',
                'price' => 1100,
                'stock' => 5,
                'brand' => 'apple',
                'category' => 'smartphones',
                'sku' => 'iphon-new',
                'image' => 'products/iphone-used.jpg'
            ],
            [
                'name' => 'Samsung Galaxy مستخدم',
                'description' => 'هاتف سامسونج جالاكسي مستخدم',
                'price' => 800,
                'stock' => 8,
                'brand' => 'samsung',
                'category' => 'smartphones',
                'sku' => 'samsag',
                'image' => 'products/samsung.jpg'
            ],
            [
                'name' => 'تلفون مترو',
                'description' => 'تلفون مترو عادي بسعر مناسب',
                'price' => 200,
                'stock' => 15,
                'brand' => 'other',
                'category' => 'mobiles',
                'sku' => 'phone',
                'image' => 'products/metro-phone.jpg'
            ],
            [
                'name' => 'Samsung Galaxy A80',
                'description' => 'Samsung Galaxy A80 بكاميرا دوارة فريدة',
                'price' => 400,
                'stock' => 10,
                'brand' => 'samsung',
                'category' => 'smartphones',
                'sku' => 'samsung-a80',
                'image' => 'products/samsung-a80.jpg'
            ],
            [
                'name' => 'Redmi Note 12 12GB+256GB',
                'description' => 'ريدمي نوت 12 بذاكرة 12 جيجا و256 تخزين',
                'price' => 320,
                'stock' => 12,
                'brand' => 'redmi',
                'category' => 'smartphones',
                'sku' => 'redmi-note-12',
                'image' => 'products/redmi-note-12.jpg'
            ],
            [
                'name' => 'Honor X9b',
                'description' => 'هاتف هونر اكس 9 بي الجيل الخامس',
                'price' => 450,
                'stock' => 8,
                'brand' => 'honor',
                'category' => 'smartphones',
                'sku' => 'honor-x9b',
                'image' => 'products/honor-x9b.jpg'
            ],
            [
                'name' => 'iPhone 13',
                'description' => 'آيفون 13 بمعالج A15 Bionic',
                'price' => 950,
                'stock' => 6,
                'brand' => 'apple',
                'category' => 'smartphones',
                'sku' => 'iphone-13',
                'image' => 'products/iphone-13.jpg'
            ],
            [
                'name' => 'Xiaomi Mi 11',
                'description' => 'شاومي مي 11 بكاميرا 108 ميجابكسل',
                'price' => 550,
                'stock' => 10,
                'brand' => 'xiaomi',
                'category' => 'smartphones',
                'sku' => 'xiaomi-mi11',
                'image' => 'products/xiaomi-mi11.jpg'
            ],
            [
                'name' => 'Oppo Reno 8',
                'description' => 'أوبو رينو 8 بتصميم أنيق',
                'price' => 420,
                'stock' => 9,
                'brand' => 'oppo',
                'category' => 'smartphones',
                'sku' => 'oppo-reno8',
                'image' => 'products/oppo-reno8.jpg'
            ],

            // الساعات الذكية
            [
                'name' => 'Apple Watch Ultra 2',
                'description' => 'ابل واتش الترا 2 ساعة ذكية بميزات متقدمة',
                'price' => 850,
                'stock' => 5,
                'brand' => 'apple',
                'category' => 'watches',
                'sku' => 'apple-watch-ultra2',
                'image' => 'products/apple-watch-ultra2.jpg'
            ],
            [
                'name' => 'Xiaomi Smart Band 7',
                'description' => 'سوار شاومي الذكي 7',
                'price' => 45,
                'stock' => 20,
                'brand' => 'xiaomi',
                'category' => 'watches',
                'sku' => 'xiaomi-band7',
                'image' => 'products/xiaomi-band7.jpg'
            ],
            [
                'name' => 'Samsung Galaxy Watch 5',
                'description' => 'ساعة سامسونج جالاكسي واتش 5',
                'price' => 280,
                'stock' => 8,
                'brand' => 'samsung',
                'category' => 'watches',
                'sku' => 'samsung-watch5',
                'image' => 'products/samsung-watch5.jpg'
            ],

            // الطابعات
            [
                'name' => 'طابعة ابسون',
                'description' => 'طابعة ابسون متعددة الوظائف',
                'price' => 2500,
                'stock' => 4,
                'brand' => 'epson',
                'category' => 'printers',
                'sku' => 'A1112',
                'image' => 'products/epson-printer.jpg'
            ],
            [
                'name' => 'طابعة HP DeskJet',
                'description' => 'طابعة HP ديسك جيت',
                'price' => 1800,
                'stock' => 6,
                'brand' => 'hp',
                'category' => 'printers',
                'sku' => 'hp-deskjet',
                'image' => 'products/hp-deskjet.jpg'
            ],
            [
                'name' => 'طابعة Canon Pixma',
                'description' => 'طابعة كانون بيكسما',
                'price' => 2200,
                'stock' => 5,
                'brand' => 'canon',
                'category' => 'printers',
                'sku' => 'canon-pixma',
                'image' => 'products/canon-pixma.jpg'
            ],

            // اللابتوبات
            [
                'name' => 'Dell Latitude',
                'description' => 'لابتوب Dell Latitude للأعمال',
                'price' => 3500,
                'stock' => 3,
                'brand' => 'dell',
                'category' => 'laptops',
                'sku' => 'dell-latitude',
                'image' => 'products/dell-latitude.jpg'
            ],
            [
                'name' => 'HP Pavilion',
                'description' => 'لابتوب HP Pavilion',
                'price' => 3200,
                'stock' => 4,
                'brand' => 'hp',
                'category' => 'laptops',
                'sku' => 'hp-pavilion',
                'image' => 'products/hp-pavilion.jpg'
            ],
            [
                'name' => 'Lenovo ThinkPad',
                'description' => 'لابتوب Lenovo ThinkPad',
                'price' => 3800,
                'stock' => 3,
                'brand' => 'lenovo',
                'category' => 'laptops',
                'sku' => 'lenovo-thinkpad',
                'image' => 'products/lenovo-thinkpad.jpg'
            ],
            [
                'name' => 'MacBook Air M2',
                'description' => 'ماك بوك اير بمعالج M2',
                'price' => 5500,
                'stock' => 2,
                'brand' => 'apple',
                'category' => 'laptops',
                'sku' => 'macbook-air-m2',
                'image' => 'products/macbook-air.jpg'
            ],

            // الإكسسوارات - سماعات
            [
                'name' => 'سماعات JBL Wave 200',
                'description' => 'سماعات JBL لاسلكية',
                'price' => 85,
                'stock' => 15,
                'brand' => 'jbl',
                'category' => 'headphones',
                'sku' => 'jbl-wave200',
                'image' => 'products/jbl-wave200.jpg'
            ],
            [
                'name' => 'AirPods Pro 2',
                'description' => 'سماعات ايربودز برو 2 مع إلغاء الضوضاء',
                'price' => 280,
                'stock' => 10,
                'brand' => 'apple',
                'category' => 'headphones',
                'sku' => 'airpods-pro2',
                'image' => 'products/airpods-pro2.jpg'
            ],
            [
                'name' => 'Beats Studio Buds',
                'description' => 'سماعات Beats Studio Buds',
                'price' => 180,
                'stock' => 12,
                'brand' => 'beats',
                'category' => 'headphones',
                'sku' => 'beats-studio',
                'image' => 'products/beats-studio.jpg'
            ],

            // شواحن وكيابل
            [
                'name' => 'شاحن Anker 65W',
                'description' => 'شاحن سريع من Anker بقوة 65 واط',
                'price' => 45,
                'stock' => 25,
                'brand' => 'anker',
                'category' => 'chargers-cables',
                'sku' => 'anker-65w',
                'image' => 'products/anker-charger.jpg'
            ],
            [
                'name' => 'كيبل USB-C Anker',
                'description' => 'كيبل USB-C من Anker بطول 2 متر',
                'price' => 18,
                'stock' => 40,
                'brand' => 'anker',
                'category' => 'chargers-cables',
                'sku' => 'anker-cable',
                'image' => 'products/anker-cable.jpg'
            ],
            [
                'name' => 'بطارية Powerology 20000mAh',
                'description' => 'بطارية محمولة من Powerology',
                'price' => 65,
                'stock' => 18,
                'brand' => 'powerology',
                'category' => 'chargers-cables',
                'sku' => 'powerology-20000',
                'image' => 'products/powerology-battery.jpg'
            ],
            [
                'name' => 'شاحن Porodo سريع',
                'description' => 'شاحن Porodo للشحن السريع',
                'price' => 38,
                'stock' => 22,
                'brand' => 'porodo',
                'category' => 'chargers-cables',
                'sku' => 'porodo-charger',
                'image' => 'products/porodo-charger.jpg'
            ],

            // أغطية وحافظات
            [
                'name' => 'حافظة Green Lion لآيفون',
                'description' => 'حافظة حماية من Green Lion',
                'price' => 25,
                'stock' => 30,
                'brand' => 'green-lion',
                'category' => 'cases-covers',
                'sku' => 'greenlion-case',
                'image' => 'products/greenlion-case.jpg'
            ],
            [
                'name' => 'حافظة Levelo متعددة الألوان',
                'description' => 'حافظة أنيقة من Levelo',
                'price' => 22,
                'stock' => 35,
                'brand' => 'levelo',
                'category' => 'cases-covers',
                'sku' => 'levelo-case',
                'image' => 'products/levelo-case.jpg'
            ],

            // منتجات أخرى
            [
                'name' => 'GoPro Hero 11',
                'description' => 'كاميرا GoPro Hero 11 مقاومة للماء',
                'price' => 520,
                'stock' => 7,
                'brand' => 'gopro',
                'category' => 'accessories',
                'sku' => 'gopro-hero11',
                'image' => 'products/gopro-hero11.jpg'
            ],
            [
                'name' => 'ميكروفون Rode Wireless',
                'description' => 'ميكروفون Rode لاسلكي للفيديو',
                'price' => 380,
                'stock' => 5,
                'brand' => 'rode',
                'category' => 'accessories',
                'sku' => 'rode-wireless',
                'image' => 'products/rode-mic.jpg'
            ],
            [
                'name' => 'ذاكرة Sandisk 128GB',
                'description' => 'ذاكرة Sandisk بسعة 128 جيجا',
                'price' => 32,
                'stock' => 50,
                'brand' => 'sandisk',
                'category' => 'accessories',
                'sku' => 'sandisk-128gb',
                'image' => 'products/sandisk-128.jpg'
            ],
        ];

        foreach ($products as $productData) {
            $brand = Brand::where('slug', $productData['brand'])->first();
            $category = Category::where('slug', $productData['category'])->first();

            if (!$brand || !$category) {
                $this->command->warn("تحذير: العلامة أو القسم غير موجود للمنتج: {$productData['name']}");
                continue;
            }

            $product = Product::updateOrCreate(
                ['sku' => $productData['sku']],
                [
                    'name' => $productData['name'],
                    'slug' => Str::slug($productData['name']),
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'stock' => $productData['stock'],
                    'brand_id' => $brand->id,
                    'image' => $productData['image'],
                ]
            );

            // ربط المنتج بالقسم
            $product->categories()->syncWithoutDetaching([$category->id]);
        }

        $this->command->info('✅ تم استيراد ' . count($products) . ' منتج من موقع أبديت');
        $this->command->info('🌐 المصدر: update-aden.com');
    }
}
