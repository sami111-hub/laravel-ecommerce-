<?php
/**
 * سكريبت إضافة علامتين تجاريتين: Anker SoundCore و ZTE RedMagic
 * يُنفَّذ مرة واحدة ثم يُحذف
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Brand;
use Illuminate\Support\Facades\DB;

echo "=== إضافة العلامات التجارية ===\n\n";

$brands = [
    [
        'name'        => 'Anker SoundCore',
        'slug'        => 'anker-soundcore',
        'description' => 'علامة Anker SoundCore متخصصة في سماعات الأذن والإكسسوارات الصوتية عالية الجودة بأسعار تنافسية. تشمل منتجاتها سماعات بلوتوث، بنوك الطاقة، والشواحن السريعة.',
        'logo'        => '',
        'is_active'   => 1,
    ],
    [
        'name'        => 'ZTE RedMagic',
        'slug'        => 'zte-redmagic',
        'description' => 'ZTE RedMagic علامة تجارية متخصصة في هواتف الألعاب عالية الأداء. تتميز هواتفها بمعالجات قوية، شاشات عالية التردد، وتصميم Gaming مميز.',
        'logo'        => '',
        'is_active'   => 1,
    ],
];

$added   = 0;
$skipped = 0;

foreach ($brands as $brandData) {
    $exists = Brand::where('slug', $brandData['slug'])->first();
    if ($exists) {
        echo "⏭️  موجودة مسبقاً: {$brandData['name']} (ID: {$exists->id})\n";
        $skipped++;
    } else {
        $brand = Brand::create($brandData);
        echo "✅ تمت الإضافة: {$brand->name} (ID: {$brand->id})\n";
        $added++;
    }
}

echo "\n=== النتيجة ===\n";
echo "✅ مضافة: $added\n";
echo "⏭️  موجودة: $skipped\n\n";

echo "=== قائمة جميع العلامات التجارية ===\n";
Brand::orderBy('id')->get()->each(function ($b) {
    echo "  [{$b->id}] {$b->name} ({$b->slug})\n";
});
