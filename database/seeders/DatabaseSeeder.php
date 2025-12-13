<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🔄 جاري استيراد بيانات موقع أبديت تكنولوجي...');
        $this->command->info('🌐 المصدر: update-aden.com');
        $this->command->newLine();

        // استيراد البيانات من موقع أبديت
        $this->call([
            CategorySeeder::class,           // الأقسام
            BrandSeeder::class,              // العلامات التجارية
            UpdateAdenProductsSeeder::class, // المنتجات من موقع أبديت
            PhonesSeeder::class,             // الهواتف في جدول منفصل
            RolePermissionSeeder::class,     // الأدوار والصلاحيات
            OrdersSeeder::class,             // طلبات تجريبية
        ]);

        // إنشاء مستخدم عادي للاختبار
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@updateaden.com',
            'password' => bcrypt('password123'),
        ]);

        $this->command->newLine();
        $this->command->info('✅ تم استيراد جميع بيانات موقع أبديت بنجاح!');
        $this->command->info('📱 الأقسام: الهواتف، الساعات، اللابتوبات، الطابعات، الإكسسوارات');
        $this->command->info('🏷️ العلامات: Apple, Samsung, Xiaomi, Redmi, Anker, JBL وغيرها');
        $this->command->info('📦 المنتجات: أكثر من 30 منتج متنوع');
        $this->command->info('🔐 نظام الصلاحيات مفعل بالكامل');
    }
}
