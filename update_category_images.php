<?php
// سكريبت لتحديث صور الفئات بصور معبرة من Unsplash

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Category;

// صور Unsplash معبرة عن كل فئة
$categoryImages = [
    'smartphones' => [
        'name' => 'الهواتف الذكية',
        'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&h=600&fit=crop&q=80',
        'icon' => '📱'
    ],
    'mobiles' => [
        'name' => 'جوالات',
        'image' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=800&h=600&fit=crop&q=80',
        'icon' => '📱'
    ],
    'laptops' => [
        'name' => 'اللابتوبات',
        'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&h=600&fit=crop&q=80',
        'icon' => '💻'
    ],
    'watches' => [
        'name' => 'الساعات الذكية',
        'image' => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=800&h=600&fit=crop&q=80',
        'icon' => '⌚'
    ],
    'printers' => [
        'name' => 'الطابعات',
        'image' => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=800&h=600&fit=crop&q=80',
        'icon' => '🖨️'
    ],
    'accessories' => [
        'name' => 'الإكسسوارات',
        'image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=800&h=600&fit=crop&q=80',
        'icon' => '🎧'
    ],
    'headphones' => [
        'name' => 'السماعات',
        'image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&h=600&fit=crop&q=80',
        'icon' => '🎧'
    ],
    'tablets' => [
        'name' => 'الأجهزة اللوحية',
        'image' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800&h=600&fit=crop&q=80',
        'icon' => '📲'
    ],
    'chargers-cables' => [
        'name' => 'شواحن وكيابل',
        'image' => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=800&h=600&fit=crop&q=80',
        'icon' => '🔌'
    ],
    'cases-covers' => [
        'name' => 'أغطية وحافظات',
        'image' => 'https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?w=800&h=600&fit=crop&q=80',
        'icon' => '🛡️'
    ],
];

echo "🔄 بدء تحديث صور الفئات...\n";
echo str_repeat("=", 60) . "\n\n";

$updated = 0;
$notFound = 0;

foreach ($categoryImages as $slug => $data) {
    $category = Category::where('slug', $slug)->first();
    
    if ($category) {
        // تحديث الصورة والأيقونة
        $category->image = $data['image'];
        $category->save();
        
        echo "✅ تحديث: {$data['name']} ({$slug})\n";
        echo "   📸 الصورة: {$data['image']}\n";
        echo "   {$data['icon']} الأيقونة: {$data['icon']}\n\n";
        
        $updated++;
    } else {
        echo "⚠️ لم يتم العثور على الفئة: {$slug}\n\n";
        $notFound++;
    }
}

echo str_repeat("=", 60) . "\n";
echo "📊 الملخص:\n";
echo "   ✅ تم التحديث: $updated فئة\n";
echo "   ⚠️ لم يتم العثور عليها: $notFound فئة\n";
echo "   📁 إجمالي الفئات: " . ($updated + $notFound) . "\n";
echo "\n✨ انتهى التحديث!\n";

// طباعة قائمة الفئات الموجودة في قاعدة البيانات
echo "\n" . str_repeat("=", 60) . "\n";
echo "📋 الفئات الموجودة في قاعدة البيانات:\n";
echo str_repeat("=", 60) . "\n\n";

$allCategories = Category::orderBy('order')->get();
foreach ($allCategories as $cat) {
    echo "• {$cat->name} ({$cat->slug})\n";
    echo "  الترتيب: {$cat->order} | نشط: " . ($cat->is_active ? 'نعم' : 'لا') . "\n";
    if ($cat->parent_id) {
        $parent = Category::find($cat->parent_id);
        echo "  الفئة الأب: {$parent->name}\n";
    }
    echo "\n";
}
