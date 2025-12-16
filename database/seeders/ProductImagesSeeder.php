<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductImagesSeeder extends Seeder
{
    public function run()
    {
        // مصفوفة الصور حسب الكلمات المفتاحية - صور عالية الجودة من Unsplash
        $imageMap = [
            // الهواتف الذكية - iPhone
            'iPhone 15' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=800&h=800&fit=crop&q=80',
            'iPhone 14' => 'https://images.unsplash.com/photo-1678652197950-1c6ebfdaa8b6?w=800&h=800&fit=crop&q=80',
            'iPhone 13' => 'https://images.unsplash.com/photo-1632661674596-df8be070a5c7?w=800&h=800&fit=crop&q=80',
            'iPhone 12' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=800&fit=crop&q=80',
            'iPhone' => 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=800&h=800&fit=crop&q=80',
            
            // Samsung
            'Galaxy S24' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=800&h=800&fit=crop&q=80',
            'Galaxy S23' => 'https://images.unsplash.com/photo-1610945264803-c22b62d2a7b6?w=800&h=800&fit=crop&q=80',
            'Galaxy S22' => 'https://images.unsplash.com/photo-1610945264803-c22b62d2a7b6?w=800&h=800&fit=crop&q=80',
            'Galaxy A54' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&h=800&fit=crop&q=80',
            'Galaxy A' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&h=800&fit=crop&q=80',
            'Galaxy Z Fold' => 'https://images.unsplash.com/photo-1585060544812-6b45742d762f?w=800&h=800&fit=crop&q=80',
            'Galaxy' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=800&h=800&fit=crop&q=80',
            'Samsung' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=800&h=800&fit=crop&q=80',
            
            // Xiaomi
            'Xiaomi 14' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=800&h=800&fit=crop&q=80',
            'Xiaomi 13' => 'https://images.unsplash.com/photo-1592286927505-b04c4e9cf7eb?w=800&h=800&fit=crop&q=80',
            'Xiaomi' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=800&h=800&fit=crop&q=80',
            'Redmi Note' => 'https://images.unsplash.com/photo-1567581935884-3349723552ca?w=800&h=800&fit=crop&q=80',
            'Redmi' => 'https://images.unsplash.com/photo-1567581935884-3349723552ca?w=800&h=800&fit=crop&q=80',
            'Poco' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&h=800&fit=crop&q=80',
            
            // Oppo & Realme
            'Oppo' => 'https://images.unsplash.com/photo-1585060544812-6b45742d762f?w=800&h=800&fit=crop&q=80',
            'Realme' => 'https://images.unsplash.com/photo-1574944985070-8f3ebc6b79d2?w=800&h=800&fit=crop&q=80',
            
            // Huawei
            'Huawei' => 'https://images.unsplash.com/photo-1591122947157-26bad3a117d2?w=800&h=800&fit=crop&q=80',
            'Honor' => 'https://images.unsplash.com/photo-1591122947157-26bad3a117d2?w=800&h=800&fit=crop&q=80',
            
            // اللابتوبات - MacBook
            'MacBook Pro' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800&h=800&fit=crop&q=80',
            'MacBook Air' => 'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?w=800&h=800&fit=crop&q=80',
            'MacBook' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800&h=800&fit=crop&q=80',
            
            // Dell
            'Dell XPS' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=800&h=800&fit=crop&q=80',
            'Dell Inspiron' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=800&h=800&fit=crop&q=80',
            'Dell' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=800&h=800&fit=crop&q=80',
            
            // HP
            'HP Pavilion' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&h=800&fit=crop&q=80',
            'HP Envy' => 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=800&h=800&fit=crop&q=80',
            'HP EliteBook' => 'https://images.unsplash.com/photo-1522199755839-a2bacb67c546?w=800&h=800&fit=crop&q=80',
            'HP' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&h=800&fit=crop&q=80',
            
            // Lenovo
            'ThinkPad' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=800&h=800&fit=crop&q=80',
            'IdeaPad' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=800&h=800&fit=crop&q=80',
            'Lenovo' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=800&h=800&fit=crop&q=80',
            
            // Asus
            'ROG' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=800&h=800&fit=crop&q=80',
            'ZenBook' => 'https://images.unsplash.com/photo-1526738549149-8e07eca6c147?w=800&h=800&fit=crop&q=80',
            'VivoBook' => 'https://images.unsplash.com/photo-1625842268584-8f3296236761?w=800&h=800&fit=crop&q=80',
            'Asus' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=800&h=800&fit=crop&q=80',
            
            // الساعات الذكية
            'Apple Watch' => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=800&h=800&fit=crop&q=80',
            'Galaxy Watch' => 'https://images.unsplash.com/photo-1617043983671-adaadcaa2460?w=800&h=800&fit=crop&q=80',
            'Xiaomi Watch' => 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=800&h=800&fit=crop&q=80',
            'Huawei Watch' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&h=800&fit=crop&q=80',
            'ساعة' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&h=800&fit=crop&q=80',
            'Watch' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&h=800&fit=crop&q=80',
            
            // الطابعات
            'HP LaserJet' => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=800&h=800&fit=crop&q=80',
            'Canon Pixma' => 'https://images.unsplash.com/photo-1606800052052-a08af7148866?w=800&h=800&fit=crop&q=80',
            'Epson' => 'https://images.unsplash.com/photo-1613236716415-b2b60443a8df?w=800&h=800&fit=crop&q=80',
            'Brother' => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=800&h=800&fit=crop&q=80',
            'طابعة' => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=800&h=800&fit=crop&q=80',
            'Printer' => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=800&h=800&fit=crop&q=80',
            
            // السماعات
            'AirPods Pro' => 'https://images.unsplash.com/photo-1606220838315-056192d5e927?w=800&h=800&fit=crop&q=80',
            'AirPods' => 'https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?w=800&h=800&fit=crop&q=80',
            'Sony WH' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&h=800&fit=crop&q=80',
            'JBL' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&h=800&fit=crop&q=80',
            'Galaxy Buds' => 'https://images.unsplash.com/photo-1590658165737-15a047b7a48c?w=800&h=800&fit=crop&q=80',
            'Beats' => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800&h=800&fit=crop&q=80',
            'سماعة' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&h=800&fit=crop&q=80',
            'Headphone' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&h=800&fit=crop&q=80',
            'Earbuds' => 'https://images.unsplash.com/photo-1606220838315-056192d5e927?w=800&h=800&fit=crop&q=80',
            
            // الشواحن
            'شاحن' => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=800&h=800&fit=crop&q=80',
            'Charger' => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=800&h=800&fit=crop&q=80',
            'Power Adapter' => 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=800&h=800&fit=crop&q=80',
            
            // الباور بانك
            'باور بانك' => 'https://images.unsplash.com/photo-1609592046276-f0a3f8d06333?w=800&h=800&fit=crop&q=80',
            'Power Bank' => 'https://images.unsplash.com/photo-1609592046276-f0a3f8d06333?w=800&h=800&fit=crop&q=80',
            'بطارية' => 'https://images.unsplash.com/photo-1609592046276-f0a3f8d06333?w=800&h=800&fit=crop&q=80',
            
            // الكوابل
            'كابل' => 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=800&h=800&fit=crop&q=80',
            'Cable' => 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=800&h=800&fit=crop&q=80',
            'USB' => 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=800&h=800&fit=crop&q=80',
            'Type-C' => 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=800&h=800&fit=crop&q=80',
            
            // الكاميرات
            'Canon EOS' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=800&h=800&fit=crop&q=80',
            'Sony Alpha' => 'https://images.unsplash.com/photo-1606986628828-c8eb4e2f2fab?w=800&h=800&fit=crop&q=80',
            'Nikon' => 'https://images.unsplash.com/photo-1606986628789-14d7687f33f1?w=800&h=800&fit=crop&q=80',
            'GoPro' => 'https://images.unsplash.com/photo-1574523842761-e0259d1ac07e?w=800&h=800&fit=crop&q=80',
            'كاميرا' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=800&h=800&fit=crop&q=80',
            'Camera' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=800&h=800&fit=crop&q=80',
            
            // الجرابات
            'جراب' => 'https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?w=800&h=800&fit=crop&q=80',
            'Case' => 'https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?w=800&h=800&fit=crop&q=80',
            'Cover' => 'https://images.unsplash.com/photo-1556656793-08538906a9f8?w=800&h=800&fit=crop&q=80',
            'حافظة' => 'https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?w=800&h=800&fit=crop&q=80',
            
            // الميكروفونات
            'ميكروفون' => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=800&h=800&fit=crop&q=80',
            'Microphone' => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=800&h=800&fit=crop&q=80',
            'Rode' => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=800&h=800&fit=crop&q=80',
            
            // الذاكرة
            'ذاكرة' => 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=800&h=800&fit=crop&q=80',
            'Memory' => 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=800&h=800&fit=crop&q=80',
            'SD Card' => 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=800&h=800&fit=crop&q=80',
            'Sandisk' => 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=800&h=800&fit=crop&q=80',
            
            // الحوامل
            'حامل' => 'https://images.unsplash.com/photo-1616353071588-48c3d64b0da5?w=800&h=800&fit=crop&q=80',
            'Stand' => 'https://images.unsplash.com/photo-1616353071588-48c3d64b0da5?w=800&h=800&fit=crop&q=80',
            'Holder' => 'https://images.unsplash.com/photo-1616353071588-48c3d64b0da5?w=800&h=800&fit=crop&q=80',
            
            // شاشات الحماية
            'شاشة حماية' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=800&h=800&fit=crop&q=80',
            'Screen Protector' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=800&h=800&fit=crop&q=80',
            'Glass' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=800&h=800&fit=crop&q=80',
            
            // التابلت
            'iPad' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800&h=800&fit=crop&q=80',
            'Galaxy Tab' => 'https://images.unsplash.com/photo-1561154464-82e9adf32764?w=800&h=800&fit=crop&q=80',
            'Tablet' => 'https://images.unsplash.com/photo-1585790050230-5dd28404f869?w=800&h=800&fit=crop&q=80',
            'تابلت' => 'https://images.unsplash.com/photo-1585790050230-5dd28404f869?w=800&h=800&fit=crop&q=80',
            
            // الشاشات
            'Monitor' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800&h=800&fit=crop&q=80',
            'شاشة' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800&h=800&fit=crop&q=80',
            
            // لوحة المفاتيح والماوس
            'Keyboard' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&h=800&fit=crop&q=80',
            'Mouse' => 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=800&h=800&fit=crop&q=80',
            'لوحة مفاتيح' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&h=800&fit=crop&q=80',
            'ماوس' => 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=800&h=800&fit=crop&q=80',
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
            
            // إذا لم يتم العثور على صورة، استخدم صورة افتراضية بناءً على الفئة
            if (!$imageFound) {
                $defaultImages = [
                    'smartphone' => 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=800&h=800&fit=crop&q=80',
                    'laptop' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&h=800&fit=crop&q=80',
                    'watch' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&h=800&fit=crop&q=80',
                    'tablet' => 'https://images.unsplash.com/photo-1585790050230-5dd28404f869?w=800&h=800&fit=crop&q=80',
                    'accessory' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&h=800&fit=crop&q=80',
                ];
                
                $defaultImage = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&h=800&fit=crop&q=80';
                
                // محاولة تحديد الصورة بناءً على الفئة
                $categories = $product->categories;
                foreach ($categories as $category) {
                    $slug = strtolower($category->slug);
                    foreach ($defaultImages as $key => $img) {
                        if (str_contains($slug, $key)) {
                            $defaultImage = $img;
                            break 2;
                        }
                    }
                }
                
                $product->image = $defaultImage;
                $product->save();
                $updated++;
                echo "⚠ تم استخدام صورة افتراضية: {$product->name}\n";
            }
        }
        
        echo "\n✅ تم تحديث {$updated} منتج من أصل {$products->count()}\n";
    }
}
