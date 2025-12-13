<?php
// سكريبت لتحديث صور المنتجات بروابط Unsplash الموثوقة

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

// صور Unsplash موثوقة ومناسبة لكل منتج
$productImages = [
    'iPhone مستخدم' => 'https://images.unsplash.com/photo-1592286927505-b04c4e9cf7eb?w=600&h=600&fit=crop&q=80',
    'Samsung Galaxy مستخدم' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=600&h=600&fit=crop&q=80',
    'تلفون مترو' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&h=600&fit=crop&q=80',
    'Samsung Galaxy A80' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=600&h=600&fit=crop&q=80',
    'Redmi Note 12 12GB+256GB' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=600&h=600&fit=crop&q=80',
    'Honor X9b' => 'https://images.unsplash.com/photo-1574944985070-8f3ebc6b79d2?w=600&h=600&fit=crop&q=80',
    'iPhone 13' => 'https://images.unsplash.com/photo-1632661674596-df8be070a5c7?w=600&h=600&fit=crop&q=80',
    'Xiaomi Mi 11' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=600&h=600&fit=crop&q=80',
    'Oppo Reno 8' => 'https://images.unsplash.com/photo-1585060544812-6b45742d762f?w=600&h=600&fit=crop&q=80',
    'Apple Watch Ultra 2' => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=600&h=600&fit=crop&q=80',
    'Xiaomi Smart Band 7' => 'https://images.unsplash.com/photo-1575311373937-040b8e1fd5b6?w=600&h=600&fit=crop&q=80',
    'Samsung Galaxy Watch 5' => 'https://images.unsplash.com/photo-1617043983671-adaadcaa2460?w=600&h=600&fit=crop&q=80',
    'طابعة ابسون' => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=600&h=600&fit=crop&q=80',
    'طابعة HP DeskJet' => 'https://images.unsplash.com/photo-1606800052052-a08af7148866?w=600&h=600&fit=crop&q=80',
    'طابعة Canon Pixma' => 'https://images.unsplash.com/photo-1613236716415-b2b60443a8df?w=600&h=600&fit=crop&q=80',
    'Dell Latitude' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&h=600&fit=crop&q=80',
    'HP Pavilion' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600&h=600&fit=crop&q=80',
    'Lenovo ThinkPad' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=600&h=600&fit=crop&q=80',
    'MacBook Air M2' => 'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?w=600&h=600&fit=crop&q=80',
    'سماعات JBL Wave 200' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=600&h=600&fit=crop&q=80',
    'AirPods Pro 2' => 'https://images.unsplash.com/photo-1606220838315-056192d5e927?w=600&h=600&fit=crop&q=80',
    'Beats Studio Buds' => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=600&h=600&fit=crop&q=80',
    'شاحن Anker 65W' => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=600&h=600&fit=crop&q=80',
    'كيبل USB-C Anker' => 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=600&h=600&fit=crop&q=80',
    'بطارية Powerology 20000mAh' => 'https://images.unsplash.com/photo-1609592046276-f0a3f8d06333?w=600&h=600&fit=crop&q=80',
    'شاحن Porodo سريع' => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=600&h=600&fit=crop&q=80',
    'حافظة Green Lion لآيفون' => 'https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?w=600&h=600&fit=crop&q=80',
    'حافظة Levelo متعددة الألوان' => 'https://images.unsplash.com/photo-1556656793-08538906a9f8?w=600&h=600&fit=crop&q=80',
    'GoPro Hero 11' => 'https://images.unsplash.com/photo-1574523842761-e0259d1ac07e?w=600&h=600&fit=crop&q=80',
    'ميكروفون Rode Wireless' => 'https://images.unsplash.com/photo-1589003077984-894e133dabab?w=600&h=600&fit=crop&q=80',
    'ذاكرة Sandisk 128GB' => 'https://images.unsplash.com/photo-1624823183493-ed5832f48f18?w=600&h=600&fit=crop&q=80',
    'iPhone pro 13' => 'https://images.unsplash.com/photo-1632661674596-df8be070a5c7?w=600&h=600&fit=crop&q=80',
    'iPhone pro 14' => 'https://images.unsplash.com/photo-1678652197950-1c6ebfdaa8b6?w=600&h=600&fit=crop&q=80',
];

$products = Product::all();
$updated = 0;

foreach ($products as $product) {
    if (isset($productImages[$product->name])) {
        $product->image = $productImages[$product->name];
        $product->save();
        $updated++;
        echo "✓ تم تحديث: {$product->name}\n";
    } else {
        echo "⚠ لم يتم العثور على صورة لـ: {$product->name}\n";
    }
}

echo "\n✅ تم تحديث {$updated} منتج بنجاح\n";
echo "📊 الإجمالي: " . $products->count() . " منتج\n";
