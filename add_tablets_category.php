<?php
// سكريبت لإضافة فئة الأجهزة اللوحية

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Category;

echo "🔄 إضافة فئة الأجهزة اللوحية...\n";
echo str_repeat("=", 60) . "\n\n";

$tabletCategory = Category::updateOrCreate(
    ['slug' => 'tablets'],
    [
        'name' => 'الأجهزة اللوحية',
        'slug' => 'tablets',
        'description' => 'آيباد وتابلت من أفضل الماركات',
        'icon' => 'bi-tablet',
        'image' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800&h=600&fit=crop&q=80',
        'order' => 4,
        'is_active' => true
    ]
);

echo "✅ تمت إضافة فئة: {$tabletCategory->name}\n";
echo "   📸 الصورة: {$tabletCategory->image}\n";
echo "   📊 الترتيب: {$tabletCategory->order}\n";
echo "\n✨ تم بنجاح!\n";
