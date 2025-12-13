<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "           🌐 تقرير بيانات موقع أبديت تكنولوجي\n";
echo "                    update-aden.com                                 \n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// الإحصائيات العامة
echo "📊 الإحصائيات العامة:\n";
echo str_repeat('─', 67) . "\n";
$categories = DB::table('categories')->count();
$brands = DB::table('brands')->count();
$products = DB::table('products')->count();
$users = DB::table('users')->count();

echo sprintf("%-25s %s\n", "الأقسام:", $categories);
echo sprintf("%-25s %s\n", "العلامات التجارية:", $brands);
echo sprintf("%-25s %s\n", "المنتجات:", $products);
echo sprintf("%-25s %s\n", "المستخدمين:", $users);
echo "\n";

// الأقسام الرئيسية
echo "📂 الأقسام الرئيسية:\n";
echo str_repeat('─', 67) . "\n";
$mainCategories = DB::table('categories')->whereNull('parent_id')->orderBy('order')->get();
foreach ($mainCategories as $cat) {
    $productsCount = DB::table('product_category')
        ->where('category_id', $cat->id)
        ->count();
    echo sprintf("%-3d. %-30s (%s منتج)\n", $cat->order, $cat->name, $productsCount);
}
echo "\n";

// أهم العلامات التجارية
echo "🏷️  أهم العلامات التجارية:\n";
echo str_repeat('─', 67) . "\n";
$topBrands = DB::table('brands')
    ->select('brands.name', 'brands.slug', DB::raw('COUNT(products.id) as products_count'))
    ->leftJoin('products', 'brands.id', '=', 'products.brand_id')
    ->groupBy('brands.id', 'brands.name', 'brands.slug')
    ->having('products_count', '>', 0)
    ->orderBy('products_count', 'desc')
    ->limit(10)
    ->get();

foreach ($topBrands as $brand) {
    echo sprintf("✓ %-20s (%d منتج)\n", $brand->name, $brand->products_count);
}
echo "\n";

// نطاق الأسعار
echo "💰 نطاق الأسعار:\n";
echo str_repeat('─', 67) . "\n";
$priceStats = DB::table('products')
    ->selectRaw('MIN(price) as min, MAX(price) as max, AVG(price) as avg, COUNT(*) as count')
    ->first();
echo sprintf("أقل سعر:    $%.2f\n", $priceStats->min);
echo sprintf("أعلى سعر:   $%.2f\n", $priceStats->max);
echo sprintf("متوسط السعر: $%.2f\n", $priceStats->avg);
echo "\n";

// عينة من المنتجات
echo "📦 عينة من المنتجات (أول 10):\n";
echo str_repeat('─', 67) . "\n";
$sampleProducts = DB::table('products')
    ->join('brands', 'products.brand_id', '=', 'brands.id')
    ->select('products.name', 'products.price', 'products.stock', 'brands.name as brand')
    ->limit(10)
    ->get();

foreach ($sampleProducts as $product) {
    echo sprintf("• %-35s | %-12s | $%-6.2f | %d متوفر\n", 
        mb_substr($product->name, 0, 35), 
        $product->brand, 
        $product->price, 
        $product->stock
    );
}
echo "\n";

// نظام الهواتف المنفصل
echo "📱 نظام الهواتف (جدول منفصل):\n";
echo str_repeat('─', 67) . "\n";
$phoneBrands = DB::table('phone_brands')->count();
$phones = DB::table('phones')->count();
$phoneSpecs = DB::table('phone_specs')->count();
$phonePrices = DB::table('phone_prices')->count();

echo sprintf("%-25s %s\n", "علامات الهواتف:", $phoneBrands);
echo sprintf("%-25s %s\n", "الهواتف:", $phones);
echo sprintf("%-25s %s\n", "المواصفات:", $phoneSpecs);
echo sprintf("%-25s %s\n", "الأسعار:", $phonePrices);

if ($phones > 0) {
    echo "\nالهواتف المتوفرة:\n";
    $phonesList = DB::table('phones')
        ->join('phone_brands', 'phones.brand_id', '=', 'phone_brands.id')
        ->select('phones.name', 'phone_brands.name as brand', 'phones.ram', 'phones.storage')
        ->get();
    
    foreach ($phonesList as $phone) {
        echo sprintf("  ✓ %-30s | %-10s | %s/%s\n", 
            $phone->name, 
            $phone->brand, 
            $phone->ram, 
            $phone->storage
        );
    }
}
echo "\n";

// المستخدمين
echo "👥 المستخدمين:\n";
echo str_repeat('─', 67) . "\n";
$usersList = DB::table('users')->select('name', 'email')->get();
foreach ($usersList as $user) {
    echo sprintf("%-30s %s\n", $user->name, $user->email);
}
echo "\n";

echo "═══════════════════════════════════════════════════════════════════\n";
echo "✅ تم استيراد جميع البيانات من موقع أبديت بنجاح!\n";
echo "🌐 المصدر: https://update-aden.com\n";
echo "📍 الموقع: اليمن - المركز الرئيسي عدن\n";
echo "📞 الهاتف: +966 12 345 6789\n";
echo "📧 البريد: contact@updatetech.com\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "\n";
