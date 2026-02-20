<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // أولاً: تحديث slugs التصنيفات القديمة إلى الجديدة
        $slugMigrations = [
            'laptops' => 'computers-tablets',
            'tablets' => null, // سيتم دمجها مع computers-tablets
            'watches' => 'smartwatches-wearables',
            'headphones' => 'headphones-speakers',
            'chargers-cables' => 'power-banks-chargers',
            'chargers' => 'power-banks-chargers',
            'cases-covers' => 'phone-accessories',
            'accessories' => 'phone-accessories',
            'gaming' => 'video-games',
            'offers' => 'special-offers',
            'mobiles' => 'smartphones',
            'printers' => null, // سيتم حذفها
        ];

        foreach ($slugMigrations as $oldSlug => $newSlug) {
            $oldCategory = Category::where('slug', $oldSlug)->first();
            if ($oldCategory) {
                if ($newSlug === null) {
                    // نقل المنتجات إلى أقرب تصنيف قبل الحذف
                    $this->command->info("⚠️ تصنيف '{$oldSlug}' سيتم حذفه");
                    // لا نحذف هنا - سنتركه غير نشط
                    $oldCategory->update(['is_active' => false]);
                } else {
                    $newExists = Category::where('slug', $newSlug)->first();
                    if ($newExists && $newExists->id !== $oldCategory->id) {
                        // التصنيف الجديد موجود بالفعل - ننقل المنتجات من القديم للجديد
                        $oldCategory->products()->each(function ($product) use ($newExists, $oldCategory) {
                            if (!$product->categories()->where('categories.id', $newExists->id)->exists()) {
                                $product->categories()->attach($newExists->id);
                            }
                            $product->categories()->detach($oldCategory->id);
                        });
                        $oldCategory->update(['is_active' => false]);
                        $this->command->info("🔀 نُقلت منتجات '{$oldSlug}' إلى '{$newSlug}'");
                    } else if (!$newExists) {
                        // التصنيف الجديد غير موجود - نحدث slug القديم
                        $oldCategory->update(['slug' => $newSlug]);
                        $this->command->info("✏️ تم تحديث slug '{$oldSlug}' → '{$newSlug}'");
                    }
                }
            }
        }

        // التصنيفات الرئيسية للمتجر
        $categories = [
            [
                'name' => 'ألعاب الفيديو',
                'slug' => 'video-games',
                'description' => 'بلايستيشن , مراوح تبريد , يدات ألعاب وغيرها',
                'icon' => 'bi-controller',
                'parent_id' => null,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'الهواتف الذكية',
                'slug' => 'smartphones',
                'description' => 'أحدث الهواتف الذكية من جميع العلامات التجارية',
                'icon' => 'bi-phone',
                'parent_id' => null,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'الكمبيوتر والتابليت',
                'slug' => 'computers-tablets',
                'description' => 'لابتوبات آبل ماك، آيباد، سامسونج , هونر , شاومي و...',
                'icon' => 'bi-laptop',
                'parent_id' => null,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'الساعات الذكية والأجهزة القابلة للإرتداء',
                'slug' => 'smartwatches-wearables',
                'description' => 'ساعات ذكية من Apple , Galaxy , Huawei , Honor وغير...',
                'icon' => 'bi-smartwatch',
                'parent_id' => null,
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'ملحقات الهواتف الذكية',
                'slug' => 'phone-accessories',
                'description' => 'إكسسوارات الهواتف والأجهزة اللوحية وملحقاتهم',
                'icon' => 'bi-phone-flip',
                'parent_id' => null,
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'سماعات ومكبرات الصوت',
                'slug' => 'headphones-speakers',
                'description' => 'سماعات Sony ,JBL, Beats وغيرها',
                'icon' => 'bi-headphones',
                'parent_id' => null,
                'order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'خزائن الطاقة والشواحن',
                'slug' => 'power-banks-chargers',
                'description' => 'خوازن , شواحن , كيبل شحن , توصيلات متنوعة',
                'icon' => 'bi-battery-charging',
                'parent_id' => null,
                'order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'عروض مميزة',
                'slug' => 'special-offers',
                'description' => 'عروض وخصومات حصرية من أبديت',
                'icon' => 'bi-tag',
                'parent_id' => null,
                'order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('✅ تم استيراد ' . count($categories) . ' قسم');
    }
}
