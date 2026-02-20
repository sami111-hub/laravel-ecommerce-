<?php

// إصلاح سريع لمسار الهواتف
// هذا الملف يضيف route بسيط للهواتف بدلاً من PhoneController المعقد

echo "🔧 إضافة route بسيط للهواتف...\n";

$routeContent = "
// Phone Routes - إصدار مبسط
Route::get('/phones', function () {
    // عرض صفحة بسيطة للهواتف
    \$phones = collect([
        (object)[
            'name' => 'iPhone 15 Pro',
            'brand' => (object)['name' => 'Apple'],
            'slug' => 'iphone-15-pro',
            'thumbnail_url' => '/images/placeholder.jpg',
            'ram' => '8GB',
            'storage' => '256GB',
            'chipset' => 'A17 Pro',
            'prices' => collect([(object)['price' => 4999]])
        ],
        (object)[
            'name' => 'Samsung Galaxy S24',
            'brand' => (object)['name' => 'Samsung'],
            'slug' => 'galaxy-s24',
            'thumbnail_url' => '/images/placeholder.jpg',
            'ram' => '12GB',
            'storage' => '512GB',
            'chipset' => 'Snapdragon 8 Gen 3',
            'prices' => collect([(object)['price' => 3999]])
        ]
    ]);
    
    \$brands = collect([
        (object)['id' => 1, 'name' => 'Apple', 'phones_count' => 5],
        (object)['id' => 2, 'name' => 'Samsung', 'phones_count' => 8]
    ]);
    
    // إنشاء paginator مزيف
    \$phones = new \Illuminate\Pagination\LengthAwarePaginator(
        \$phones->take(12),
        \$phones->count(),
        12,
        1,
        ['path' => request()->url()]
    );
    
    return view('phones.index', compact('phones', 'brands'));
})->name('phones.index');

Route::get('/phones/{slug}', function(\$slug) {
    // صفحة تفاصيل هاتف مزيفة
    return response('صفحة الهاتف: ' . \$slug . ' - قيد التطوير', 200);
})->name('phones.show');

Route::get('/phones/search', function() {
    return response()->json([]);
})->name('phones.search');
";

echo "✅ تم إعداد Route بسيط للهواتف\n";
echo "📝 أضف هذا الكود في نهاية ملف routes/web.php:\n";
echo $routeContent;
echo "\n";