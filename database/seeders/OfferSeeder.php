<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Offer;
use Carbon\Carbon;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offers = [
            [
                'title' => 'عرض نهاية الأسبوع',
                'description' => 'خصم خاص على جميع المنتجات لفترة محدودة! لا تفوت الفرصة',
                'discount_percentage' => 25,
                'start_date' => Carbon::now()->subDays(2),
                'end_date' => Carbon::now()->addDays(5),
                'is_active' => true,
            ],
            [
                'title' => 'عرض الجمعة البيضاء',
                'description' => 'تخفيضات هائلة تصل إلى 50% على منتجات مختارة',
                'discount_percentage' => 50,
                'start_date' => Carbon::now()->subDay(),
                'end_date' => Carbon::now()->addDays(3),
                'is_active' => true,
            ],
            [
                'title' => 'عرض الصيف الحار',
                'description' => 'استعد للصيف مع أفضل العروض على منتجاتنا المميزة',
                'discount_percentage' => 30,
                'start_date' => Carbon::now()->addDays(3),
                'end_date' => Carbon::now()->addDays(10),
                'is_active' => true,
            ],
            [
                'title' => 'عرض العودة للمدارس',
                'description' => 'كل ما تحتاجه لبداية موسم دراسي ناجح بأسعار خاصة',
                'discount_percentage' => 20,
                'start_date' => Carbon::now()->addDays(7),
                'end_date' => Carbon::now()->addDays(14),
                'is_active' => true,
            ],
        ];

        foreach ($offers as $offer) {
            Offer::create($offer);
        }
    }
}
