<?php
/**
 * 🔍 سكريبت اختبار شامل للوحة الإدارة
 * يختبر جميع صفحات ووظائف لوحة التحكم قبل إطلاق الموقع
 */

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Offer;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

echo "\n";
echo "╔══════════════════════════════════════════════════════════════════╗\n";
echo "║       🔍 اختبار شامل للوحة الإدارة - قبل الإطلاق                ║\n";
echo "║                  Laravel E-Commerce Admin Panel Test             ║\n";
echo "╚══════════════════════════════════════════════════════════════════╝\n\n";

$results = [];
$errors = [];
$warnings = [];

// ========================================
// 1. اختبار الاتصال بقاعدة البيانات
// ========================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📦 1. اختبار الاتصال بقاعدة البيانات\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

try {
    DB::connection()->getPdo();
    $dbName = DB::connection()->getDatabaseName();
    echo "   ✅ الاتصال بقاعدة البيانات: {$dbName} - ناجح\n";
    $results['database_connection'] = true;
} catch (\Exception $e) {
    echo "   ❌ فشل الاتصال بقاعدة البيانات: {$e->getMessage()}\n";
    $results['database_connection'] = false;
    $errors[] = "فشل الاتصال بقاعدة البيانات";
}

// ========================================
// 2. اختبار الجداول الأساسية
// ========================================
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 2. اختبار الجداول والبيانات الأساسية\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$tables = [
    'users' => User::class,
    'products' => Product::class,
    'categories' => Category::class,
    'orders' => Order::class,
    'roles' => Role::class,
    'permissions' => Permission::class,
    'offers' => Offer::class,
];

foreach ($tables as $tableName => $model) {
    try {
        $count = $model::count();
        echo "   ✅ جدول {$tableName}: {$count} سجل\n";
        $results["table_{$tableName}"] = ['exists' => true, 'count' => $count];
    } catch (\Exception $e) {
        echo "   ❌ جدول {$tableName}: خطأ - {$e->getMessage()}\n";
        $results["table_{$tableName}"] = ['exists' => false, 'error' => $e->getMessage()];
        $errors[] = "مشكلة في جدول {$tableName}";
    }
}

// ========================================
// 3. اختبار المستخدم المدير
// ========================================
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "👤 3. اختبار المستخدمين والأدوار\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

// البحث عن مستخدم مدير
$adminUser = User::whereHas('roles', function($q) {
    $q->where('name', 'super-admin')->orWhere('name', 'admin');
})->first();

if ($adminUser) {
    echo "   ✅ المستخدم المدير موجود: {$adminUser->name} ({$adminUser->email})\n";
    
    // التحقق من أدوار المستخدم
    $roles = $adminUser->roles->pluck('name')->toArray();
    echo "   📋 الأدوار: " . implode(', ', $roles) . "\n";
    
    // التحقق من الصلاحيات
    $permissions = $adminUser->getAllPermissions();
    echo "   🔑 عدد الصلاحيات: " . count($permissions) . "\n";
    
    $results['admin_user'] = true;
} else {
    echo "   ⚠️ لا يوجد مستخدم مدير!\n";
    $warnings[] = "لا يوجد مستخدم مدير - قد تحتاج لإنشاء واحد";
    
    // محاولة إيجاد أي مستخدم
    $anyUser = User::first();
    if ($anyUser) {
        echo "   ℹ️ يوجد مستخدم: {$anyUser->name} - يمكن ترقيته لمدير\n";
    }
    $results['admin_user'] = false;
}

// اختبار الأدوار
echo "\n   📋 الأدوار المتاحة:\n";
$allRoles = Role::withCount('users')->get();
foreach ($allRoles as $role) {
    $status = $role->is_active ? '✓' : '✗';
    echo "      {$status} {$role->display_name} ({$role->name}): {$role->users_count} مستخدم\n";
}

// ========================================
// 4. اختبار الصلاحيات
// ========================================
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔐 4. اختبار الصلاحيات\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$permissionsByGroup = Permission::where('is_active', true)->get()->groupBy('group');
foreach ($permissionsByGroup as $group => $permissions) {
    echo "   📁 {$group}: " . count($permissions) . " صلاحية\n";
}

$totalPermissions = Permission::where('is_active', true)->count();
echo "   📊 إجمالي الصلاحيات النشطة: {$totalPermissions}\n";
$results['permissions'] = ['total' => $totalPermissions, 'groups' => $permissionsByGroup->count()];

// ========================================
// 5. اختبار المنتجات
// ========================================
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📦 5. اختبار المنتجات\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$productsCount = Product::count();
$activeProducts = Product::where('is_active', true)->count();
$inStockProducts = Product::where('stock', '>', 0)->count();
$outOfStock = Product::where('stock', '<=', 0)->count();
$withImages = Product::whereNotNull('image')->count();

echo "   📊 إجمالي المنتجات: {$productsCount}\n";
echo "   ✅ منتجات نشطة: {$activeProducts}\n";
echo "   📦 منتجات متوفرة: {$inStockProducts}\n";
echo "   ⚠️ منتجات نفذت: {$outOfStock}\n";
echo "   🖼️ منتجات بصور: {$withImages}\n";

if ($outOfStock > 0) {
    $warnings[] = "يوجد {$outOfStock} منتج نفذ من المخزون";
}

// فحص منتجات بدون صور
$withoutImages = Product::whereNull('image')->orWhere('image', '')->get();
if ($withoutImages->count() > 0) {
    echo "   ⚠️ منتجات بدون صور: {$withoutImages->count()}\n";
    foreach ($withoutImages->take(5) as $p) {
        echo "      - {$p->name}\n";
    }
    $warnings[] = "يوجد {$withoutImages->count()} منتج بدون صور";
}

$results['products'] = [
    'total' => $productsCount,
    'active' => $activeProducts,
    'in_stock' => $inStockProducts,
    'out_of_stock' => $outOfStock,
];

// ========================================
// 6. اختبار التصنيفات
// ========================================
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🏷️ 6. اختبار التصنيفات\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$categories = Category::withCount('products')->get();
echo "   📊 إجمالي التصنيفات: {$categories->count()}\n\n";

foreach ($categories as $category) {
    $hasImage = !empty($category->image) ? '🖼️' : '⚠️';
    echo "   {$hasImage} {$category->name}: {$category->products_count} منتج\n";
}

$emptyCategories = $categories->filter(function($c) { return $c->products_count == 0; });
if ($emptyCategories->count() > 0) {
    $warnings[] = "يوجد {$emptyCategories->count()} تصنيف بدون منتجات";
}

$results['categories'] = ['total' => $categories->count(), 'empty' => $emptyCategories->count()];

// ========================================
// 7. اختبار الطلبات
// ========================================
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🛒 7. اختبار الطلبات\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$ordersCount = Order::count();
$ordersByStatus = Order::selectRaw('status, count(*) as count')->groupBy('status')->get();

echo "   📊 إجمالي الطلبات: {$ordersCount}\n";
foreach ($ordersByStatus as $os) {
    $statusEmoji = [
        'pending' => '⏳',
        'processing' => '🔄',
        'shipped' => '🚚',
        'delivered' => '✅',
        'cancelled' => '❌',
    ];
    $emoji = $statusEmoji[$os->status] ?? '📋';
    echo "   {$emoji} {$os->status}: {$os->count}\n";
}

$totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');
echo "   💰 إجمالي الإيرادات: \${$totalRevenue}\n";

$results['orders'] = ['total' => $ordersCount, 'revenue' => $totalRevenue];

// ========================================
// 8. اختبار العروض
// ========================================
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🏷️ 8. اختبار العروض\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$activeOffers = Offer::where('is_active', true)
    ->where(function($q) {
        $q->whereNull('end_date')
          ->orWhere('end_date', '>=', now());
    })->count();
$expiredOffers = Offer::where('end_date', '<', now())->count();
$totalOffers = Offer::count();

echo "   📊 إجمالي العروض: {$totalOffers}\n";
echo "   ✅ عروض نشطة: {$activeOffers}\n";
echo "   ⏰ عروض منتهية: {$expiredOffers}\n";

if ($expiredOffers > 0) {
    $warnings[] = "يوجد {$expiredOffers} عرض منتهي الصلاحية";
}

$results['offers'] = ['total' => $totalOffers, 'active' => $activeOffers, 'expired' => $expiredOffers];

// ========================================
// 9. اختبار إعدادات الموقع
// ========================================
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "⚙️ 9. اختبار إعدادات الموقع\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

try {
    $promoText = SiteSetting::get('promo_bar_text', '');
    $promoEnabled = SiteSetting::get('promo_bar_enabled', '1');
    
    echo "   📢 الشريط الترويجي:\n";
    echo "      - الحالة: " . ($promoEnabled == '1' ? '✅ مفعل' : '❌ معطل') . "\n";
    echo "      - النص: " . (strlen($promoText) > 50 ? mb_substr($promoText, 0, 50) . '...' : $promoText) . "\n";
    
    if (empty($promoText)) {
        $warnings[] = "الشريط الترويجي فارغ";
    }
    
    $results['promo_bar'] = ['enabled' => $promoEnabled == '1', 'has_text' => !empty($promoText)];
} catch (\Exception $e) {
    echo "   ⚠️ خطأ في قراءة الإعدادات: {$e->getMessage()}\n";
    $warnings[] = "مشكلة في جدول الإعدادات";
}

// ========================================
// 10. اختبار ملفات العرض (Views)
// ========================================
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📄 10. اختبار ملفات العرض (Views)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$viewsToCheck = [
    'admin.dashboard' => 'resources/views/admin/dashboard.blade.php',
    'admin.layout' => 'resources/views/admin/layout.blade.php',
    'admin.products.index' => 'resources/views/admin/products/index.blade.php',
    'admin.products.create' => 'resources/views/admin/products/create.blade.php',
    'admin.products.edit' => 'resources/views/admin/products/edit.blade.php',
    'admin.categories.index' => 'resources/views/admin/categories/index.blade.php',
    'admin.orders.index' => 'resources/views/admin/orders/index.blade.php',
    'admin.users.index' => 'resources/views/admin/users/index.blade.php',
    'admin.roles.index' => 'resources/views/admin/roles/index.blade.php',
    'admin.permissions.index' => 'resources/views/admin/permissions/index.blade.php',
    'admin.offers.index' => 'resources/views/admin/offers/index.blade.php',
    'admin.settings.promo-bar' => 'resources/views/admin/settings/promo-bar.blade.php',
];

$basePath = __DIR__ . '/../';
foreach ($viewsToCheck as $viewName => $viewPath) {
    $fullPath = $basePath . $viewPath;
    if (file_exists($fullPath)) {
        echo "   ✅ {$viewName}\n";
    } else {
        echo "   ❌ {$viewName} - غير موجود!\n";
        $errors[] = "ملف العرض غير موجود: {$viewPath}";
    }
}

// ========================================
// 11. اختبار Controllers
// ========================================
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🎮 11. اختبار Controllers\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$controllers = [
    'App\\Http\\Controllers\\Admin\\DashboardController',
    'App\\Http\\Controllers\\Admin\\ProductController',
    'App\\Http\\Controllers\\Admin\\CategoryController',
    'App\\Http\\Controllers\\Admin\\OrderController',
    'App\\Http\\Controllers\\Admin\\UserManagementController',
    'App\\Http\\Controllers\\Admin\\RoleController',
    'App\\Http\\Controllers\\Admin\\PermissionController',
    'App\\Http\\Controllers\\Admin\\OfferController',
    'App\\Http\\Controllers\\Admin\\SiteSettingsController',
];

foreach ($controllers as $controller) {
    if (class_exists($controller)) {
        echo "   ✅ " . class_basename($controller) . "\n";
    } else {
        echo "   ❌ " . class_basename($controller) . " - غير موجود!\n";
        $errors[] = "Controller غير موجود: {$controller}";
    }
}

// ========================================
// 12. اختبار Routes
// ========================================
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🛣️ 12. اختبار المسارات (Routes)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$routes = app('router')->getRoutes();
$adminRoutes = [];

foreach ($routes as $route) {
    $name = $route->getName();
    if ($name && strpos($name, 'admin.') === 0) {
        $adminRoutes[] = $name;
    }
}

echo "   📊 إجمالي مسارات الإدارة: " . count($adminRoutes) . "\n";

$routeGroups = [
    'admin.dashboard' => 'لوحة التحكم',
    'admin.products' => 'المنتجات',
    'admin.categories' => 'التصنيفات',
    'admin.orders' => 'الطلبات',
    'admin.users' => 'المستخدمين',
    'admin.roles' => 'الأدوار',
    'admin.permissions' => 'الصلاحيات',
    'admin.offers' => 'العروض',
    'admin.settings' => 'الإعدادات',
];

foreach ($routeGroups as $prefix => $label) {
    $count = count(array_filter($adminRoutes, function($r) use ($prefix) {
        return strpos($r, $prefix) === 0;
    }));
    echo "   ✅ {$label}: {$count} مسار\n";
}

// ========================================
// 13. اختبار الصور والملفات
// ========================================
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🖼️ 13. اختبار مجلدات الصور\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$imageDirs = [
    'public/images' => 'صور عامة',
    'public/images/products' => 'صور المنتجات',
    'public/images/categories' => 'صور التصنيفات',
    'storage/app/public' => 'تخزين عام',
];

foreach ($imageDirs as $dir => $label) {
    $fullPath = $basePath . $dir;
    if (is_dir($fullPath)) {
        $files = glob($fullPath . '/*');
        echo "   ✅ {$label}: " . count($files) . " ملف\n";
    } else {
        echo "   ⚠️ {$label}: المجلد غير موجود\n";
    }
}

// فحص symbolic link للتخزين
$storageLink = $basePath . 'public/storage';
if (is_link($storageLink) || is_dir($storageLink)) {
    echo "   ✅ رابط التخزين (storage link) موجود\n";
} else {
    echo "   ⚠️ رابط التخزين غير موجود - قم بتشغيل: php artisan storage:link\n";
    $warnings[] = "رابط التخزين غير موجود";
}

// ========================================
// 14. اختبار Middleware
// ========================================
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔒 14. اختبار Middleware\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$middlewares = [
    'App\\Http\\Middleware\\CheckPermission' => 'فحص الصلاحيات',
];

foreach ($middlewares as $middleware => $label) {
    if (class_exists($middleware)) {
        echo "   ✅ {$label}\n";
    } else {
        echo "   ⚠️ {$label} - غير موجود\n";
    }
}

// ========================================
// 15. ملخص النتائج
// ========================================
echo "\n";
echo "╔══════════════════════════════════════════════════════════════════╗\n";
echo "║                      📋 ملخص نتائج الاختبار                      ║\n";
echo "╚══════════════════════════════════════════════════════════════════╝\n\n";

$errorCount = count($errors);
$warningCount = count($warnings);

if ($errorCount == 0 && $warningCount == 0) {
    echo "🎉 ممتاز! جميع الاختبارات ناجحة - الموقع جاهز للإطلاق!\n\n";
} else {
    if ($errorCount > 0) {
        echo "❌ الأخطاء ({$errorCount}):\n";
        foreach ($errors as $i => $error) {
            echo "   " . ($i + 1) . ". {$error}\n";
        }
        echo "\n";
    }
    
    if ($warningCount > 0) {
        echo "⚠️ التحذيرات ({$warningCount}):\n";
        foreach ($warnings as $i => $warning) {
            echo "   " . ($i + 1) . ". {$warning}\n";
        }
        echo "\n";
    }
}

// إحصائيات سريعة
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 إحصائيات الموقع:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "   👥 المستخدمين: " . User::count() . "\n";
echo "   📦 المنتجات: " . Product::count() . "\n";
echo "   🏷️ التصنيفات: " . Category::count() . "\n";
echo "   🛒 الطلبات: " . Order::count() . "\n";
echo "   🎁 العروض: " . Offer::count() . "\n";
echo "   🔐 الأدوار: " . Role::count() . "\n";
echo "   🔑 الصلاحيات: " . Permission::count() . "\n";

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔗 روابط لوحة الإدارة للاختبار اليدوي:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
$baseUrl = env('APP_URL', 'http://localhost');
echo "   🏠 الرئيسية: {$baseUrl}/admin\n";
echo "   📦 المنتجات: {$baseUrl}/admin/products\n";
echo "   🏷️ التصنيفات: {$baseUrl}/admin/categories\n";
echo "   🛒 الطلبات: {$baseUrl}/admin/orders\n";
echo "   👥 المستخدمين: {$baseUrl}/admin/users\n";
echo "   🔐 الأدوار: {$baseUrl}/admin/roles\n";
echo "   🔑 الصلاحيات: {$baseUrl}/admin/permissions\n";
echo "   🎁 العروض: {$baseUrl}/admin/offers\n";
echo "   📢 الشريط الترويجي: {$baseUrl}/admin/settings/promo-bar\n";

echo "\n✅ انتهى الاختبار!\n\n";

// Return results for programmatic use
return [
    'success' => $errorCount == 0,
    'errors' => $errors,
    'warnings' => $warnings,
    'results' => $results,
];
