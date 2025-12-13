<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // تحديث صور المنتجات بروابط من الإنترنت
        
        // الهواتف الذكية
        DB::table('products')->where('name', 'LIKE', '%iPhone 15 Pro Max%')->update([
            'image' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%Samsung Galaxy S24 Ultra%')->update([
            'image' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%Xiaomi 14 Pro%')->update([
            'image' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%Oppo Find X7%')->update([
            'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%Realme GT 5%')->update([
            'image' => 'https://images.unsplash.com/photo-1585060544812-6b45742d762f?w=500&h=500&fit=crop'
        ]);
        
        // اللابتوبات
        DB::table('products')->where('name', 'LIKE', '%MacBook Pro 16%')->update([
            'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%Dell XPS 15%')->update([
            'image' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%HP Pavilion%')->update([
            'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%Lenovo ThinkPad%')->update([
            'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%Asus ROG%')->update([
            'image' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=500&h=500&fit=crop'
        ]);
        
        // الساعات الذكية
        DB::table('products')->where('name', 'LIKE', '%Apple Watch Series 9%')->update([
            'image' => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%Samsung Galaxy Watch%')->update([
            'image' => 'https://images.unsplash.com/photo-1617043983671-adaadcaa2460?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%Xiaomi Watch%')->update([
            'image' => 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=500&h=500&fit=crop'
        ]);
        
        // الطابعات
        DB::table('products')->where('name', 'LIKE', '%HP LaserJet%')->update([
            'image' => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%Canon Pixma%')->update([
            'image' => 'https://images.unsplash.com/photo-1606800052052-a08af7148866?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%Epson EcoTank%')->update([
            'image' => 'https://images.unsplash.com/photo-1613236716415-b2b60443a8df?w=500&h=500&fit=crop'
        ]);
        
        // السماعات
        DB::table('products')->where('name', 'LIKE', '%AirPods Pro%')->update([
            'image' => 'https://images.unsplash.com/photo-1606220838315-056192d5e927?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%Sony WH-1000XM5%')->update([
            'image' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%JBL%')->update([
            'image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%Samsung Galaxy Buds%')->update([
            'image' => 'https://images.unsplash.com/photo-1590658165737-15a047b7a48c?w=500&h=500&fit=crop'
        ]);
        
        // الشواحن والكوابل
        DB::table('products')->where('name', 'LIKE', '%شاحن%')->orWhere('name', 'LIKE', '%Charger%')->update([
            'image' => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%كابل%')->orWhere('name', 'LIKE', '%Cable%')->update([
            'image' => 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%باور بانك%')->orWhere('name', 'LIKE', '%Power Bank%')->update([
            'image' => 'https://images.unsplash.com/photo-1609592046276-f0a3f8d06333?w=500&h=500&fit=crop'
        ]);
        
        // الكاميرات
        DB::table('products')->where('name', 'LIKE', '%Canon EOS%')->update([
            'image' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%Sony Alpha%')->update([
            'image' => 'https://images.unsplash.com/photo-1606986628828-c8eb4e2f2fab?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%GoPro%')->update([
            'image' => 'https://images.unsplash.com/photo-1574523842761-e0259d1ac07e?w=500&h=500&fit=crop'
        ]);
        
        // الإكسسوارات العامة
        DB::table('products')->where('name', 'LIKE', '%جراب%')->orWhere('name', 'LIKE', '%Case%')->update([
            'image' => 'https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%حامل%')->orWhere('name', 'LIKE', '%Stand%')->update([
            'image' => 'https://images.unsplash.com/photo-1616353071588-48c3d64b0da5?w=500&h=500&fit=crop'
        ]);
        
        DB::table('products')->where('name', 'LIKE', '%شاشة حماية%')->orWhere('name', 'LIKE', '%Screen Protector%')->update([
            'image' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500&h=500&fit=crop'
        ]);
    }

    public function down()
    {
        // إرجاع الصور إلى null
        DB::table('products')->update(['image' => null]);
    }
};
