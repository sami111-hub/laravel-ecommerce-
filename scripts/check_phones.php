<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "📱 العلامات التجارية من موقع أبديت:\n";
echo str_repeat('=', 50) . "\n";

$brands = DB::table('phone_brands')->orderBy('order')->get();
foreach ($brands as $brand) {
    echo "✓ {$brand->name} ({$brand->slug})\n";
    echo "  الوصف: {$brand->description}\n";
}

echo "\n📱 الهواتف المستوردة:\n";
echo str_repeat('=', 50) . "\n";

$phones = DB::table('phones')
    ->join('phone_brands', 'phones.brand_id', '=', 'phone_brands.id')
    ->select('phones.*', 'phone_brands.name as brand_name')
    ->get();

foreach ($phones as $phone) {
    echo "\n✓ {$phone->name}\n";
    echo "  العلامة: {$phone->brand_name}\n";
    echo "  المعالج: {$phone->chipset}\n";
    echo "  الذاكرة: {$phone->ram} / {$phone->storage}\n";
    echo "  الشاشة: {$phone->display_size}\" | البطارية: {$phone->battery_mah}mAh\n";
    echo "  النظام: {$phone->os} ({$phone->release_year})\n";
    
    $price = DB::table('phone_prices')->where('phone_id', $phone->id)->where('is_current', 1)->first();
    if ($price) {
        echo "  السعر: {$price->currency} {$price->price}\n";
        echo "  المصدر: {$price->source}\n";
    }
}

echo "\n\n📊 الإحصائيات:\n";
echo str_repeat('=', 50) . "\n";
echo "العلامات التجارية: " . DB::table('phone_brands')->count() . "\n";
echo "الهواتف: " . DB::table('phones')->count() . "\n";
echo "المواصفات: " . DB::table('phone_specs')->count() . "\n";
echo "الأسعار: " . DB::table('phone_prices')->count() . "\n";
echo "\n🌐 المصدر: update-aden.com\n";
echo "📍 الموقع: اليمن - عدن\n";
