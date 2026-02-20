<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\Category;
use Illuminate\Support\Facades\DB;

echo "=== إصلاح التصنيفات ===\n\n";

// التصنيفات الصحيحة (8 فقط)
$correctCategories = [
    ['slug' => 'video-games', 'name' => 'ألعاب الفيديو', 'description' => 'بلايستيشن , مراوح تبريد , يدات ألعاب وغيرها', 'icon' => 'bi-controller', 'order' => 1],
    ['slug' => 'smartphones', 'name' => 'الهواتف الذكية', 'description' => 'أحدث الهواتف الذكية من جميع العلامات التجارية', 'icon' => 'bi-phone', 'order' => 2],
    ['slug' => 'computers-tablets', 'name' => 'الكمبيوتر والتابليت', 'description' => 'لابتوبات آبل ماك، آيباد، سامسونج , هونر , شاومي و...', 'icon' => 'bi-laptop', 'order' => 3],
    ['slug' => 'smartwatches-wearables', 'name' => 'الساعات الذكية والأجهزة القابلة للإرتداء', 'description' => 'ساعات ذكية من Apple , Galaxy , Huawei , Honor وغير...', 'icon' => 'bi-smartwatch', 'order' => 4],
    ['slug' => 'phone-accessories', 'name' => 'ملحقات الهواتف الذكية', 'description' => 'إكسسوارات الهواتف والأجهزة اللوحية وملحقاتهم', 'icon' => 'bi-phone-flip', 'order' => 5],
    ['slug' => 'headphones-speakers', 'name' => 'سماعات ومكبرات الصوت', 'description' => 'سماعات Sony ,JBL, Beats وغيرها', 'icon' => 'bi-headphones', 'order' => 6],
    ['slug' => 'power-banks-chargers', 'name' => 'خزائن الطاقة والشواحن', 'description' => 'خوازن , شواحن , كيبل شحن , توصيلات متنوعة', 'icon' => 'bi-battery-charging', 'order' => 7],
    ['slug' => 'special-offers', 'name' => 'عروض مميزة', 'description' => 'عروض وخصومات حصرية من أبديت', 'icon' => 'bi-tag', 'order' => 8],
];

// خريطة: التصنيفات الخاطئة/المكررة → التصنيف الصحيح
$mergeMap = [
    // ID:3 (laptops and Tab) → computers-tablets
    3 => 'computers-tablets',
    // ID:4 (watches and wearables) → smartwatches-wearables
    4 => 'smartwatches-wearables',
    // ID:11 (alaaab-alfydyo) → video-games
    11 => 'video-games',
];

DB::beginTransaction();

try {
    // الخطوة 1: إنشاء/تحديث التصنيفات الصحيحة
    echo "الخطوة 1: إنشاء/تحديث التصنيفات الصحيحة...\n";
    foreach ($correctCategories as $cat) {
        Category::updateOrCreate(
            ['slug' => $cat['slug']],
            array_merge($cat, ['is_active' => true, 'parent_id' => null])
        );
        echo "  ✅ {$cat['slug']} → {$cat['name']}\n";
    }

    // الخطوة 2: نقل المنتجات من التصنيفات المكررة
    echo "\nالخطوة 2: نقل المنتجات من التصنيفات المكررة...\n";
    foreach ($mergeMap as $oldId => $newSlug) {
        $oldCat = Category::find($oldId);
        $newCat = Category::where('slug', $newSlug)->first();
        
        if (!$oldCat || !$newCat) {
            echo "  ⚠️ تخطي: oldId={$oldId} أو slug={$newSlug} غير موجود\n";
            continue;
        }
        
        if ($oldCat->id === $newCat->id) {
            echo "  ⏩ تخطي: {$oldCat->slug} هو نفس التصنيف الصحيح\n";
            continue;
        }
        
        // نقل المنتجات من product_category pivot table
        $productIds = DB::table('product_category')
            ->where('category_id', $oldCat->id)
            ->pluck('product_id')
            ->toArray();
        
        if (count($productIds) > 0) {
            foreach ($productIds as $pid) {
                // تحقق أن المنتج ليس مرتبط بالفعل بالتصنيف الجديد
                $exists = DB::table('product_category')
                    ->where('product_id', $pid)
                    ->where('category_id', $newCat->id)
                    ->exists();
                
                if (!$exists) {
                    DB::table('product_category')->insert([
                        'product_id' => $pid,
                        'category_id' => $newCat->id,
                    ]);
                }
            }
            // حذف الارتباطات القديمة
            DB::table('product_category')
                ->where('category_id', $oldCat->id)
                ->delete();
            
            echo "  🔀 نُقلت " . count($productIds) . " منتج من '{$oldCat->slug}' (ID:{$oldCat->id}) إلى '{$newSlug}' (ID:{$newCat->id})\n";
        } else {
            echo "  📭 لا توجد منتجات في '{$oldCat->slug}' (ID:{$oldCat->id})\n";
        }
    }

    // الخطوة 3: حذف التصنيفات المكررة/الخاطئة
    echo "\nالخطوة 3: حذف التصنيفات المكررة...\n";
    $correctSlugs = array_column($correctCategories, 'slug');
    $toDelete = Category::whereNotIn('slug', $correctSlugs)->get();
    
    foreach ($toDelete as $cat) {
        // تحقق من عدم وجود منتجات مرتبطة
        $remaining = DB::table('product_category')->where('category_id', $cat->id)->count();
        if ($remaining > 0) {
            echo "  ⚠️ لا يمكن حذف '{$cat->slug}' (ID:{$cat->id}) - لا يزال لديه {$remaining} منتج مرتبط\n";
        } else {
            $cat->delete();
            echo "  🗑️ حذف '{$cat->slug}' (ID:{$cat->id}) - {$cat->name}\n";
        }
    }

    DB::commit();
    echo "\n✅ تم الإصلاح بنجاح!\n";

    // عرض الحالة النهائية
    echo "\n=== التصنيفات بعد الإصلاح ===\n";
    $cats = Category::orderBy('order')->get();
    foreach ($cats as $c) {
        $productCount = DB::table('product_category')->where('category_id', $c->id)->count();
        echo "ID:{$c->id} | {$c->slug} | {$c->name} | منتجات: {$productCount}\n";
    }

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ خطأ: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
