<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'لابتوب Dell XPS 15',
                'description' => 'لابتوب قوي مع معالج Intel Core i7 وشاشة 15 بوصة بدقة 4K',
                'price' => 1299.99,
                'stock' => 15,
                'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800',
                'brand_id' => 3,
                'sku' => 'LAP001',
                'slug' => 'laptop-dell-xps-15',
                'categories' => ['electronics']
            ],
            [
                'name' => 'هاتف Samsung Galaxy S24',
                'description' => 'هاتف ذكي مع شاشة Super AMOLED وكاميرا 108 ميجابكسل',
                'price' => 899.99,
                'stock' => 30,
                'image' => 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=800',
                'brand_id' => 2,
                'sku' => 'PHN001',
                'slug' => 'samsung-galaxy-s24',
                'categories' => ['electronics','smartphones']
            ],
            [
                'name' => 'سماعات AirPods Pro',
                'description' => 'سماعات لاسلكية بنظام إلغاء الضوضاء النشط وبطارية طويلة الأمد',
                'price' => 249.99,
                'stock' => 50,
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800',
                'brand_id' => 1,
                'sku' => 'AUD001',
                'slug' => 'airpods-pro',
                'categories' => ['electronics','electronics-accessories']
            ],
            [
                'name' => 'ساعة Apple Watch Series 9',
                'description' => 'ساعة ذكية مع شاشة Retina وقدرات تتبع صحية متقدمة',
                'price' => 399.99,
                'stock' => 25,
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800',
                'brand_id' => 1,
                'sku' => 'WAT001',
                'slug' => 'apple-watch-series-9',
                'categories' => ['electronics']
            ],
            [
                'name' => 'لوحة مفاتيح ميكانيكية',
                'description' => 'لوحة مفاتيح RGB ميكانيكية مع مفاتيح Cherry MX',
                'price' => 179.99,
                'stock' => 40,
                'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800',
                'brand_id' => 7,
                'sku' => 'KEY001',
                'slug' => 'mechanical-keyboard',
                'categories' => ['electronics','electronics-accessories']
            ],
            [
                'name' => 'ماوس لاسلكي',
                'description' => 'ماوس لاسلكي دقيق مع استشعار 16000 DPI وبطارية 6 أشهر',
                'price' => 79.99,
                'stock' => 60,
                'image' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=800',
                'brand_id' => 7,
                'sku' => 'MOU001',
                'slug' => 'wireless-mouse',
                'categories' => ['electronics','electronics-accessories']
            ],
            [
                'name' => 'شاشة 4K Ultra HD',
                'description' => 'شاشة LED 27 بوصة بدقة 4K مع دعم HDR10',
                'price' => 449.99,
                'stock' => 20,
                'image' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800',
                'brand_id' => 2,
                'sku' => 'MON001',
                'slug' => '4k-ultra-hd-monitor',
                'categories' => ['electronics']
            ],
            [
                'name' => 'كاميرا GoPro Hero 12',
                'description' => 'كاميرا أكشن مقاومة للماء يمكنها تصوير 4K 60fps',
                'price' => 499.99,
                'stock' => 18,
                'image' => 'https://images.unsplash.com/photo-1510127034890-ba27508e9f1c?w=800',
                'brand_id' => 7,
                'sku' => 'CAM001',
                'slug' => 'gopro-hero-12',
                'categories' => ['electronics']
            ],
            [
                'name' => 'تابلت Samsung Galaxy Tab',
                'description' => 'جهاز لوحي بشاشة 10 بوصة ومعالج قوي',
                'price' => 599.99,
                'stock' => 22,
                'image' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800',
                'brand_id' => 2,
                'sku' => 'TAB001',
                'slug' => 'samsung-galaxy-tab',
                'categories' => ['electronics']
            ],
            [
                'name' => 'لاب توب ابل MacBook Pro',
                'description' => 'لاب توب ابل بشاشة Retina ومعالج M2 Pro',
                'price' => 1599.99,
                'stock' => 12,
                'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800',
                'brand_id' => 1,
                'sku' => 'MAC001',
                'slug' => 'macbook-pro',
                'categories' => ['electronics']
            ],
            [
                'name' => 'سماعات سوني WH-1000XM4',
                'description' => 'سماعات لاسلكية مع إلغاء ضوضاء متقدم وجودة صوت استثنائية',
                'price' => 349.99,
                'stock' => 35,
                'image' => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800',
                'brand_id' => 7,
                'sku' => 'SON001',
                'slug' => 'sony-wh1000xm4',
                'categories' => ['electronics','electronics-accessories']
            ],
            [
                'name' => 'هاتف آيفون 15 Pro Max',
                'description' => 'أحدث هاتف آيفون مع شاشة Dynamic Island وكاميرا 48 ميجابكسل',
                'price' => 1199.99,
                'stock' => 18,
                'image' => 'https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?w=800',
                'brand_id' => 1,
                'sku' => 'IPH001',
                'slug' => 'iphone-15-pro-max',
                'categories' => ['electronics','smartphones']
            ],
            // Supermarket
            [
                'name' => 'أرز بسمتي 5 كجم',
                'description' => 'أرز بسمتي طويل الحبة عالي الجودة مناسب للمناسبات.',
                'price' => 12.99,
                'stock' => 120,
                'image' => 'https://images.unsplash.com/photo-1626204213351-50744b31c764?w=800',
                'brand_id' => 5,
                'sku' => 'SMK-RICE-5KG',
                'slug' => 'basmati-rice-5kg',
                'categories' => ['supermarket']
            ],
            [
                'name' => 'زيت طبخ نباتي 1.5 لتر',
                'description' => 'زيت نباتي خفيف للطهي والقلي يومياً.',
                'price' => 5.49,
                'stock' => 85,
                'image' => 'https://images.unsplash.com/photo-1542442828-28721999d3f1?w=800',
                'brand_id' => 5,
                'sku' => 'SMK-OIL-15L',
                'slug' => 'vegetable-oil-1-5l',
                'categories' => ['supermarket']
            ],
            [
                'name' => 'حليب طويل الأجل 1 لتر',
                'description' => 'حليب مبستر طويل الأجل غني بالكالسيوم.',
                'price' => 1.39,
                'stock' => 200,
                'image' => 'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=800',
                'brand_id' => 4,
                'sku' => 'DAIRY-MILK-1L',
                'slug' => 'long-life-milk-1l',
                'categories' => ['supermarket','dairy']
            ],
            [
                'name' => 'تفاح أحمر 1 كجم',
                'description' => 'تفاح طازج مقرمش غني بالألياف.',
                'price' => 2.99,
                'stock' => 90,
                'image' => 'https://images.unsplash.com/photo-1567306226416-28f0efdc88ce?w=800',
                'brand_id' => 7,
                'sku' => 'FRUIT-APPLE-1KG',
                'slug' => 'red-apple-1kg',
                'categories' => ['supermarket','fruits-vegetables']
            ],
            [
                'name' => 'ماء معبأ 24x330 مل',
                'description' => 'ماء نقي معبأ، عبوة اقتصادية 24 قارورة.',
                'price' => 3.49,
                'stock' => 140,
                'image' => 'https://images.unsplash.com/photo-1548839140-29a749e1cf4d?w=800',
                'brand_id' => 6,
                'sku' => 'BEV-WATER-24PK',
                'slug' => 'bottled-water-24x330',
                'categories' => ['supermarket','beverages']
            ],
            // Health & Beauty
            [
                'name' => 'شامبو ضد القشرة 400 مل',
                'description' => 'تركيبة فعّالة تزيل القشرة وتمنح شعرك انتعاشاً.',
                'price' => 6.99,
                'stock' => 70,
                'image' => 'https://images.unsplash.com/photo-1585386959984-a41552231658?w=800',
                'brand_id' => 6,
                'sku' => 'HB-SHAMPOO-400',
                'slug' => 'anti-dandruff-shampoo-400',
                'categories' => ['health-beauty']
            ],
            [
                'name' => 'بلسم مغذي 300 مل',
                'description' => 'يغذي الشعر ويمنحه نعومة وانسيابية.',
                'price' => 5.99,
                'stock' => 60,
                'image' => 'https://images.unsplash.com/photo-1629198735661-2433b6be9e08?w=800',
                'brand_id' => 6,
                'sku' => 'HB-CONDITIONER-300',
                'slug' => 'nourishing-conditioner-300',
                'categories' => ['health-beauty','conditioner']
            ],
            [
                'name' => 'مزيل عرق رول-أون 50 مل',
                'description' => 'حماية تدوم 48 ساعة برائحة منعشة.',
                'price' => 4.49,
                'stock' => 80,
                'image' => 'https://images.unsplash.com/photo-1611930022403-8ab1d6e7857e?w=800',
                'brand_id' => 6,
                'sku' => 'HB-DEOD-50',
                'slug' => 'deodorant-roll-on-50',
                'categories' => ['health-beauty','deodorant']
            ],

            // Cleaning & Household
            [
                'name' => 'مسحوق غسيل 5 كجم',
                'description' => 'تنظيف عميق وحماية للأقمشة.',
                'price' => 11.99,
                'stock' => 75,
                'image' => 'https://images.unsplash.com/photo-1603072389945-94336f0b29ef?w=800',
                'brand_id' => 7,
                'sku' => 'CLN-LAUND-5KG',
                'slug' => 'laundry-detergent-5kg',
                'categories' => ['laundry-detergent']
            ],
            [
                'name' => 'مناديل وجه 5 علب',
                'description' => 'مناديل ناعمة من 2 طبقة.',
                'price' => 3.29,
                'stock' => 150,
                'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800',
                'brand_id' => 7,
                'sku' => 'HHD-TISSUE-5PK',
                'slug' => 'facial-tissues-5-pack',
                'categories' => ['tissues']
            ],
            [
                'name' => 'مناديل مطبخ رول 6 قطع',
                'description' => 'امتصاص عالي ومتين.',
                'price' => 4.99,
                'stock' => 130,
                'image' => 'https://images.unsplash.com/photo-1580477371194-4593e3e7e4b5?w=800',
                'brand_id' => 7,
                'sku' => 'HHD-KITCH-ROLL-6',
                'slug' => 'kitchen-towel-roll-6',
                'categories' => ['tissues','kitchen-supplies']
            ],

            // Snacks example
            [
                'name' => 'رقائق بطاطس 170 جم',
                'description' => 'مقرمشة بنكهات متعددة.',
                'price' => 2.49,
                'stock' => 180,
                'image' => 'https://images.unsplash.com/photo-1585238342028-4bbc91a30fa1?w=800',
                'brand_id' => 7,
                'sku' => 'SNK-CHIPS-170',
                'slug' => 'potato-chips-170g',
                'categories' => ['supermarket']
            ],
            [
                'name' => 'معجون أسنان مبيض 100 مل',
                'description' => 'يساعد على إزالة البقع وتبييض الأسنان بلطف.',
                'price' => 3.99,
                'stock' => 110,
                'image' => 'https://images.unsplash.com/photo-1606813907291-76d3f2d8ed43?w=800',
                'brand_id' => 6,
                'sku' => 'HB-TOOTHPASTE-100',
                'slug' => 'whitening-toothpaste-100',
                'categories' => ['health-beauty']
            ],
            [
                'name' => 'مرطب بشرة يومي 200 مل',
                'description' => 'مرطب خفيف سريع الامتصاص مناسب لجميع أنواع البشرة.',
                'price' => 8.49,
                'stock' => 65,
                'image' => 'https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?w=800',
                'brand_id' => 6,
                'sku' => 'HB-MOIST-200',
                'slug' => 'daily-skin-moisturizer-200',
                'categories' => ['health-beauty']
            ],
        ];

        foreach ($products as $data) {
            $categorySlugs = $data['categories'] ?? [];
            $slug = $data['slug'] ?? null;
            unset($data['categories']);

            if ($slug === null) {
                continue;
            }

            $identifier = ['slug' => $slug];
            $values = $data;

            $product = Product::updateOrCreate($identifier, $values);

            if (!empty($categorySlugs)) {
                $categoryIds = Category::whereIn('slug', $categorySlugs)->pluck('id')->all();
                if (!empty($categoryIds)) {
                    $product->categories()->syncWithoutDetaching($categoryIds);
                }
            }
        }
    }
}
