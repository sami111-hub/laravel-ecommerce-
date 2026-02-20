<?php
/**
 * Script to add Anker SoundCore and ZTE Redmagic brands
 * Run: c:\xampp82\php\php.exe add_new_brands.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Brand;
use App\Models\PhoneBrand;

echo "===========================================\n";
echo "  إضافة العلامتين التجاريتين الجديدتين\n";
echo "===========================================\n\n";

// ---- 1. إضافة إلى جدول brands (المنتجات العامة) ----
$newBrands = [
    [
        'name'        => 'Anker SoundCore',
        'slug'        => 'anker-soundcore',
        'description' => 'سماعات وإكسسوارات صوتية Anker SoundCore الأصلية - جودة صوت استثنائية',
        'logo'        => 'brands/anker-soundcore.png',
        'is_active'   => true,
    ],
    [
        'name'        => 'ZTE Redmagic',
        'slug'        => 'zte-redmagic',
        'description' => 'هواتف الألعاب ZTE Redmagic - أداء خارق لتجربة ألعاب لا مثيل لها',
        'logo'        => 'brands/zte-redmagic.png',
        'is_active'   => true,
    ],
];

echo "📦 إضافة إلى جدول brands...\n";
foreach ($newBrands as $brandData) {
    $brand = Brand::updateOrCreate(
        ['slug' => $brandData['slug']],
        $brandData
    );
    echo "  ✅ {$brand->name} (ID: {$brand->id})\n";
}

// ---- 2. إضافة إلى جدول phone_brands (إذا وُجد) ----
echo "\n📱 إضافة إلى جدول phone_brands...\n";

$phoneBrandsData = [
    [
        'name'        => 'Anker SoundCore',
        'slug'        => 'anker-soundcore',
        'logo'        => 'brands/anker-soundcore.png',
        'description' => 'سماعات وإكسسوارات صوتية Anker SoundCore الأصلية',
        'is_active'   => true,
        'order'       => 10,
    ],
    [
        'name'        => 'ZTE Redmagic',
        'slug'        => 'zte-redmagic',
        'logo'        => 'brands/zte-redmagic.png',
        'description' => 'هواتف الألعاب ZTE Redmagic',
        'is_active'   => true,
        'order'       => 11,
    ],
];

try {
    foreach ($phoneBrandsData as $pbData) {
        $pb = PhoneBrand::updateOrCreate(
            ['slug' => $pbData['slug']],
            $pbData
        );
        echo "  ✅ {$pb->name} (ID: {$pb->id})\n";
    }
} catch (\Exception $e) {
    echo "  ⚠️  جدول phone_brands: " . $e->getMessage() . "\n";
}

echo "\n===========================================\n";
echo "  تم بنجاح! العلامتان التجاريتان مضافتان\n";
echo "===========================================\n";
