<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

// تصحيح أسماء المنتجات
$products = Product::where('name', 'LIKE', 'iphon%')->get();

foreach ($products as $product) {
    $product->name = str_replace('iphon', 'iPhone', $product->name);
    $product->save();
    echo "✓ تم تصحيح: {$product->name}\n";
}

echo "\n✅ تم تصحيح " . $products->count() . " منتجات\n";
