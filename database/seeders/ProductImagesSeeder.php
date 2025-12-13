<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductImagesSeeder extends Seeder
{
    public function run()
    {
        // مصفوفة الصور حسب الكلمات المفتاحية
        $imageMap = [
            // الهواتف الذكية - iPhone
            'iPhone 15' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=600&h=600&fit=crop',
            'iPhone 14' => 'https://images.unsplash.com/photo-1678652197950-1c6ebfdaa8b6?w=600&h=600&fit=crop',
            'iPhone 13' => 'https://images.unsplash.com/photo-1632661674596-df8be070a5c7?w=600&h=600&fit=crop',
            
            // Samsung
            'Galaxy S24' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=600&h=600&fit=crop',
            'Galaxy S23' => 'https://images.unsplash.com/photo-1610945264803-c22b62d2a7b6?w=600&h=600&fit=crop',
            'Galaxy A54' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&h=600&fit=crop',
            'Galaxy Z Fold' => 'https://images.unsplash.com/photo-1585060544812-6b45742d762f?w=600&h=600&fit=crop',
            
            // Xiaomi
            'Xiaomi 14' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=600&h=600&fit=crop',
            'Xiaomi 13' => 'https://images.unsplash.com/photo-1592286927505-b04c4e9cf7eb?w=600&h=600&fit=crop',
            'Redmi Note' => 'https://images.unsplash.com/photo-1567581935884-3349723552ca?w=600&h=600&fit=crop',
            'Poco' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&h=600&fit=crop',
            
            // Oppo & Realme
            'Oppo' => 'https://images.unsplash.com/photo-1585060544812-6b45742d762f?w=600&h=600&fit=crop',
            'Realme' => 'https://images.unsplash.com/photo-1574944985070-8f3ebc6b79d2?w=600&h=600&fit=crop',
            
            // Huawei
            'Huawei' => 'https://images.unsplash.com/photo-1591122947157-26bad3a117d2?w=600&h=600&fit=crop',
            
            // اللابتوبات - MacBook
            'MacBook Pro' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600&h=600&fit=crop',
            'MacBook Air' => 'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?w=600&h=600&fit=crop',
            
            // Dell
            'Dell XPS' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=600&h=600&fit=crop',
            'Dell Inspiron' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&h=600&fit=crop',
            
            // HP
            'HP Pavilion' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600&h=600&fit=crop',
            'HP Envy' => 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=600&h=600&fit=crop',
            'HP EliteBook' => 'https://images.unsplash.com/photo-1522199755839-a2bacb67c546?w=600&h=600&fit=crop',
            
            // Lenovo
            'ThinkPad' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&h=600&fit=crop',
            'IdeaPad' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=600&h=600&fit=crop',
            
            // Asus
            'ROG' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=600&h=600&fit=crop',
            'ZenBook' => 'https://images.unsplash.com/photo-1526738549149-8e07eca6c147?w=600&h=600&fit=crop',
            'VivoBook' => 'https://images.unsplash.com/photo-1625842268584-8f3296236761?w=600&h=600&fit=crop',
            
            // الساعات الذكية
            'Apple Watch' => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=600&h=600&fit=crop',
            'Galaxy Watch' => 'https://images.unsplash.com/photo-1617043983671-adaadcaa2460?w=600&h=600&fit=crop',
            'Xiaomi Watch' => 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=600&h=600&fit=crop',
            'Huawei Watch' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&h=600&fit=crop',
            
            // الطابعات
            'HP LaserJet' => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=600&h=600&fit=crop',
            'Canon Pixma' => 'https://images.unsplash.com/photo-1606800052052-a08af7148866?w=600&h=600&fit=crop',
            'Epson' => 'https://images.unsplash.com/photo-1613236716415-b2b60443a8df?w=600&h=600&fit=crop',
            'Brother' => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=600&h=600&fit=crop',
            
            // السماعات
            'AirPods Pro' => 'https://images.unsplash.com/photo-1606220838315-056192d5e927?w=600&h=600&fit=crop',
            'AirPods' => 'https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?w=600&h=600&fit=crop',
            'Sony WH' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=600&h=600&fit=crop',
            'JBL' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=600&h=600&fit=crop',
            'Galaxy Buds' => 'https://images.unsplash.com/photo-1590658165737-15a047b7a48c?w=600&h=600&fit=crop',
            'Beats' => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=600&h=600&fit=crop',
            
            // الشواحن
            'شاحن' => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=600&h=600&fit=crop',
            'Charger' => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=600&h=600&fit=crop',
            'Power Adapter' => 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=600&h=600&fit=crop',
            
            // الباور بانك
            'باور بانك' => 'https://images.unsplash.com/photo-1609592046276-f0a3f8d06333?w=600&h=600&fit=crop',
            'Power Bank' => 'https://images.unsplash.com/photo-1609592046276-f0a3f8d06333?w=600&h=600&fit=crop',
            'بطارية' => 'https://images.unsplash.com/photo-1609592046276-f0a3f8d06333?w=600&h=600&fit=crop',
            
            // الكوابل
            'كابل' => 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=600&h=600&fit=crop',
            'Cable' => 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=600&h=600&fit=crop',
            'USB' => 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=600&h=600&fit=crop',
            
            // الكاميرات
            'Canon EOS' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=600&h=600&fit=crop',
            'Sony Alpha' => 'https://images.unsplash.com/photo-1606986628828-c8eb4e2f2fab?w=600&h=600&fit=crop',
            'Nikon' => 'https://images.unsplash.com/photo-1606986628789-14d7687f33f1?w=600&h=600&fit=crop',
            'GoPro' => 'https://images.unsplash.com/photo-1574523842761-e0259d1ac07e?w=600&h=600&fit=crop',
            
            // الجرابات
            'جراب' => 'https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?w=600&h=600&fit=crop',
            'Case' => 'https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?w=600&h=600&fit=crop',
            'Cover' => 'https://images.unsplash.com/photo-1556656793-08538906a9f8?w=600&h=600&fit=crop',
            
            // الحوامل
            'حامل' => 'https://images.unsplash.com/photo-1616353071588-48c3d64b0da5?w=600&h=600&fit=crop',
            'Stand' => 'https://images.unsplash.com/photo-1616353071588-48c3d64b0da5?w=600&h=600&fit=crop',
            'Holder' => 'https://images.unsplash.com/photo-1616353071588-48c3d64b0da5?w=600&h=600&fit=crop',
            
            // شاشات الحماية
            'شاشة حماية' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=600&h=600&fit=crop',
            'Screen Protector' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=600&h=600&fit=crop',
            'Glass' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=600&h=600&fit=crop',
            
            // التابلت
            'iPad' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=600&h=600&fit=crop',
            'Galaxy Tab' => 'https://images.unsplash.com/photo-1561154464-82e9adf32764?w=600&h=600&fit=crop',
            'Tablet' => 'https://images.unsplash.com/photo-1585790050230-5dd28404f869?w=600&h=600&fit=crop',
        ];
        
        $products = Product::all();
        $updated = 0;
        
        foreach ($products as $product) {
            // إذا كان المنتج لديه صورة بالفعل، تخطيه
            if (!empty($product->image) && str_starts_with($product->image, 'http')) {
                continue;
            }
            
            $imageFound = false;
            
            // البحث عن تطابق في اسم المنتج
            foreach ($imageMap as $keyword => $imageUrl) {
                if (stripos($product->name, $keyword) !== false) {
                    $product->image = $imageUrl;
                    $product->save();
                    $updated++;
                    $imageFound = true;
                    echo "✓ تم تحديث صورة: {$product->name}\n";
                    break;
                }
            }
            
            // إذا لم يتم العثور على صورة، استخدم صورة افتراضية
            if (!$imageFound) {
                $defaultImage = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=600&fit=crop';
                $product->image = $defaultImage;
                $product->save();
                $updated++;
                echo "⚠ تم استخدام صورة افتراضية: {$product->name}\n";
            }
        }
        
        echo "\n✅ تم تحديث {$updated} منتج من أصل {$products->count()}\n";
    }
}
