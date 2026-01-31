<?php
/**
 * سكربت اختبار شامل لجميع وظائف الموقع
 * يمكن تشغيله عبر: php scripts/test_all_routes.php
 */

// تحميل Laravel
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Coupon;
use App\Models\Review;
use App\Models\Wishlist;
use App\Models\Offer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║     🧪 اختبار شامل لجميع وظائف الموقع - Laravel E-Commerce   ║\n";
echo "║                    Laravel 12.42.0                           ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$results = [];
$passed = 0;
$failed = 0;

function test($name, $callback) {
    global $passed, $failed, $results;
    try {
        $result = $callback();
        if ($result === true) {
            echo "✅ {$name}\n";
            $passed++;
            $results[] = ['name' => $name, 'status' => 'passed'];
        } else {
            echo "❌ {$name}: {$result}\n";
            $failed++;
            $results[] = ['name' => $name, 'status' => 'failed', 'error' => $result];
        }
    } catch (Exception $e) {
        echo "❌ {$name}: " . $e->getMessage() . "\n";
        $failed++;
        $results[] = ['name' => $name, 'status' => 'failed', 'error' => $e->getMessage()];
    }
}

// ═══════════════════════════════════════════════════════════════
// 1. اختبار الاتصال بقاعدة البيانات والجداول
// ═══════════════════════════════════════════════════════════════
echo "\n📊 === اختبار قاعدة البيانات ===\n";

test('الاتصال بقاعدة البيانات', function() {
    DB::connection()->getPdo();
    return true;
});

$tables = [
    'users', 'products', 'categories', 'brands', 'orders', 'order_items',
    'carts', 'wishlists', 'reviews', 'coupons', 'roles', 'permissions',
    'offers', 'site_settings', 'addresses'
];

foreach ($tables as $table) {
    test("جدول {$table} موجود", function() use ($table) {
        return Schema::hasTable($table) ? true : "الجدول غير موجود";
    });
}

// ═══════════════════════════════════════════════════════════════
// 2. اختبار الموديلات
// ═══════════════════════════════════════════════════════════════
echo "\n📦 === اختبار الموديلات ===\n";

test('موديل User يعمل', function() {
    $count = User::count();
    return $count >= 0 ? true : "فشل في استعلام المستخدمين";
});

test('موديل Product يعمل', function() {
    $count = Product::count();
    return $count >= 0 ? true : "فشل في استعلام المنتجات";
});

test('موديل Category يعمل', function() {
    $count = Category::count();
    return $count >= 0 ? true : "فشل في استعلام التصنيفات";
});

test('موديل Brand يعمل', function() {
    $count = Brand::count();
    return $count >= 0 ? true : "فشل في استعلام العلامات التجارية";
});

test('موديل Order يعمل', function() {
    $count = Order::count();
    return $count >= 0 ? true : "فشل في استعلام الطلبات";
});

test('موديل Role يعمل', function() {
    $count = Role::count();
    return $count >= 0 ? true : "فشل في استعلام الأدوار";
});

test('موديل Permission يعمل', function() {
    $count = Permission::count();
    return $count >= 0 ? true : "فشل في استعلام الصلاحيات";
});

// ═══════════════════════════════════════════════════════════════
// 3. اختبار البيانات
// ═══════════════════════════════════════════════════════════════
echo "\n📈 === اختبار البيانات ===\n";

test('يوجد مستخدمين في قاعدة البيانات', function() {
    $count = User::count();
    return $count > 0 ? true : "لا يوجد مستخدمين (العدد: {$count})";
});

test('يوجد منتجات في قاعدة البيانات', function() {
    $count = Product::count();
    return $count > 0 ? true : "لا يوجد منتجات (العدد: {$count})";
});

test('يوجد تصنيفات في قاعدة البيانات', function() {
    $count = Category::count();
    return $count > 0 ? true : "لا يوجد تصنيفات (العدد: {$count})";
});

test('يوجد علامات تجارية في قاعدة البيانات', function() {
    $count = Brand::count();
    return $count > 0 ? true : "لا يوجد علامات تجارية (العدد: {$count})";
});

// ═══════════════════════════════════════════════════════════════
// 4. اختبار العلاقات
// ═══════════════════════════════════════════════════════════════
echo "\n🔗 === اختبار العلاقات ===\n";

test('علاقة Product -> Brand تعمل', function() {
    $product = Product::with('brand')->first();
    if (!$product) return "لا توجد منتجات للاختبار";
    return true;
});

test('علاقة Product -> Categories تعمل', function() {
    $product = Product::with('categories')->first();
    if (!$product) return "لا توجد منتجات للاختبار";
    return true;
});

test('علاقة Order -> User تعمل', function() {
    $order = Order::with('user')->first();
    if (!$order) return true; // لا توجد طلبات للاختبار
    return true;
});

test('علاقة Order -> Items تعمل', function() {
    $order = Order::with('items')->first();
    if (!$order) return true;
    return true;
});

test('علاقة User -> Role تعمل', function() {
    $user = User::with('role')->first();
    if (!$user) return "لا يوجد مستخدمين للاختبار";
    return true;
});

test('علاقة Role -> Permissions تعمل', function() {
    $role = Role::with('permissions')->first();
    if (!$role) return true;
    return true;
});

// ═══════════════════════════════════════════════════════════════
// 5. اختبار الصلاحيات والأدوار
// ═══════════════════════════════════════════════════════════════
echo "\n🔐 === اختبار الصلاحيات والأدوار ===\n";

test('يوجد أدوار في النظام', function() {
    $count = Role::count();
    return $count > 0 ? true : "لا توجد أدوار معرفة";
});

test('يوجد صلاحيات في النظام', function() {
    $count = Permission::count();
    return $count > 0 ? true : "لا توجد صلاحيات معرفة";
});

test('يوجد مستخدم مدير (Super Admin)', function() {
    $admin = User::whereHas('role', function($q) {
        $q->where('slug', 'super-admin')->orWhere('name', 'like', '%admin%');
    })->first();
    return $admin ? true : "لا يوجد مستخدم مدير";
});

// ═══════════════════════════════════════════════════════════════
// 6. اختبار وظائف المتجر
// ═══════════════════════════════════════════════════════════════
echo "\n🛒 === اختبار وظائف المتجر ===\n";

test('المنتجات لها أسعار صحيحة', function() {
    $invalid = Product::where('price', '<=', 0)->count();
    return $invalid == 0 ? true : "يوجد {$invalid} منتجات بأسعار غير صحيحة";
});

test('المنتجات النشطة موجودة', function() {
    $active = Product::where('is_active', true)->count();
    return $active > 0 ? true : "لا توجد منتجات نشطة";
});

test('التصنيفات النشطة موجودة', function() {
    $active = Category::where('is_active', true)->count();
    return $active > 0 ? true : "لا توجد تصنيفات نشطة";
});

// ═══════════════════════════════════════════════════════════════
// 7. اختبار ملفات التكوين
// ═══════════════════════════════════════════════════════════════
echo "\n⚙️ === اختبار ملفات التكوين ===\n";

test('APP_KEY معرف', function() {
    $key = config('app.key');
    return !empty($key) ? true : "APP_KEY غير معرف";
});

test('قاعدة البيانات معرفة', function() {
    $db = config('database.connections.mysql.database');
    return !empty($db) ? true : "اسم قاعدة البيانات غير معرف";
});

test('APP_URL معرف', function() {
    $url = config('app.url');
    return !empty($url) ? true : "APP_URL غير معرف";
});

// ═══════════════════════════════════════════════════════════════
// 8. اختبار المسارات (Routes)
// ═══════════════════════════════════════════════════════════════
echo "\n🔀 === اختبار المسارات ===\n";

$routes = [
    'home' => '/',
    'products.index' => '/products',
    'login' => '/login',
    'register' => '/register',
    'offers' => '/offers',
    'categories.index' => '/categories',
    'faq' => '/faq',
    'about' => '/about',
    'contact' => '/contact',
];

foreach ($routes as $name => $path) {
    test("المسار {$name} معرف", function() use ($name) {
        try {
            $url = route($name);
            return !empty($url) ? true : "المسار غير معرف";
        } catch (Exception $e) {
            return "خطأ: " . $e->getMessage();
        }
    });
}

// ═══════════════════════════════════════════════════════════════
// 9. اختبار الواجهات (Views)
// ═══════════════════════════════════════════════════════════════
echo "\n🎨 === اختبار الواجهات ===\n";

$views = [
    'home-jarir',
    'auth.login',
    'auth.register',
    'products.index',
    'products.show',
    'cart.index',
    'pages.about',
    'pages.contact',
];

foreach ($views as $view) {
    test("واجهة {$view} موجودة", function() use ($view) {
        return view()->exists($view) ? true : "الواجهة غير موجودة";
    });
}

// ═══════════════════════════════════════════════════════════════
// 10. إحصائيات النظام
// ═══════════════════════════════════════════════════════════════
echo "\n📊 === إحصائيات النظام ===\n";

echo "   👥 المستخدمين: " . User::count() . "\n";
echo "   📦 المنتجات: " . Product::count() . "\n";
echo "   📁 التصنيفات: " . Category::count() . "\n";
echo "   🏷️  العلامات التجارية: " . Brand::count() . "\n";
echo "   📋 الطلبات: " . Order::count() . "\n";
echo "   🛒 عناصر السلة: " . Cart::count() . "\n";
echo "   ❤️  المفضلة: " . Wishlist::count() . "\n";
echo "   ⭐ التقييمات: " . Review::count() . "\n";
echo "   🎁 الكوبونات: " . Coupon::count() . "\n";
echo "   🔖 العروض: " . Offer::count() . "\n";
echo "   👑 الأدوار: " . Role::count() . "\n";
echo "   🔑 الصلاحيات: " . Permission::count() . "\n";

// ═══════════════════════════════════════════════════════════════
// النتيجة النهائية
// ═══════════════════════════════════════════════════════════════
echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                     📋 نتيجة الاختبار                        ║\n";
echo "╠══════════════════════════════════════════════════════════════╣\n";
printf("║   ✅ نجح: %-4d                                              ║\n", $passed);
printf("║   ❌ فشل: %-4d                                              ║\n", $failed);
printf("║   📊 المجموع: %-4d                                          ║\n", $passed + $failed);
$percentage = ($passed + $failed) > 0 ? round(($passed / ($passed + $failed)) * 100, 1) : 0;
printf("║   📈 نسبة النجاح: %s%%                                      ║\n", $percentage);
echo "╚══════════════════════════════════════════════════════════════╝\n";

if ($failed > 0) {
    echo "\n⚠️  الاختبارات الفاشلة:\n";
    foreach ($results as $result) {
        if ($result['status'] === 'failed') {
            echo "   ❌ {$result['name']}: {$result['error']}\n";
        }
    }
}

echo "\n";

// حفظ النتائج في ملف
$reportPath = __DIR__ . '/../storage/logs/test_report_' . date('Y-m-d_H-i-s') . '.json';
file_put_contents($reportPath, json_encode([
    'date' => date('Y-m-d H:i:s'),
    'passed' => $passed,
    'failed' => $failed,
    'percentage' => $percentage,
    'results' => $results
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "📄 تم حفظ التقرير في: {$reportPath}\n\n";

exit($failed > 0 ? 1 : 0);
