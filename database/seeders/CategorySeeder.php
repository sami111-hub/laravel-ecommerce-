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
        // الأقسام من موقع أبديت تكنولوجي (update-aden.com)
        $categories = [
            [
                'name' => 'الهواتف الذكية',
                'slug' => 'smartphones',
                'description' => 'أحدث الهواتف الذكية من جميع العلامات التجارية',
                'icon' => 'bi-phone',
                'parent_id' => null,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'جوالات',
                'slug' => 'mobiles',
                'description' => 'جوالات بأفضل الأسعار',
                'icon' => 'bi-phone-fill',
                'parent_id' => null,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'اللابتوبات',
                'slug' => 'laptops',
                'description' => 'لابتوبات ديل، لينوفو، أسوس وغيرها',
                'icon' => 'bi-laptop',
                'parent_id' => null,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'الساعات',
                'slug' => 'watches',
                'description' => 'ساعات ذكية من Apple, Xiaomi وغيرها',
                'icon' => 'bi-smartwatch',
                'parent_id' => null,
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'الطابعات',
                'slug' => 'printers',
                'description' => 'طابعات Epson, Canon, HP',
                'icon' => 'bi-printer',
                'parent_id' => null,
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'الإكسسوارات',
                'slug' => 'accessories',
                'description' => 'إكسسوارات الهواتف والأجهزة',
                'icon' => 'bi-headphones',
                'parent_id' => null,
                'order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'سماعات',
                'slug' => 'headphones',
                'description' => 'سماعات JBL, Beats وغيرها',
                'icon' => 'bi-earbuds',
                'parent_id' => 6,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'شواحن وكيابل',
                'slug' => 'chargers-cables',
                'description' => 'شواحن سريعة وكيابل أصلية',
                'icon' => 'bi-plug',
                'parent_id' => 6,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'أغطية وحافظات',
                'slug' => 'cases-covers',
                'description' => 'حافظات حماية للهواتف',
                'icon' => 'bi-phone-flip',
                'parent_id' => 6,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'عروض مميزة',
                'slug' => 'offers',
                'description' => 'عروض وخصومات حصرية من أبديت',
                'icon' => 'bi-tag',
                'parent_id' => null,
                'order' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('✅ تم استيراد ' . count($categories) . ' قسم من موقع أبديت');
    }
}
