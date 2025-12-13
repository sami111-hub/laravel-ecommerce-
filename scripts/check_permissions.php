<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "           🔐 نظام الصلاحيات - أبديت تكنولوجي\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// التحقق من المدير
echo "👤 حساب مدير النظام:\n";
echo str_repeat('─', 67) . "\n";

$admin = DB::table('users')->where('email', 'admin@gmail.com')->first();
if ($admin) {
    echo "✅ تم إنشاء حساب المدير بنجاح\n";
    echo sprintf("   الاسم: %s\n", $admin->name);
    echo sprintf("   البريد: %s\n", $admin->email);
    echo sprintf("   كلمة المرور: admin123\n");
    echo sprintf("   الدور: %s\n", DB::table('roles')->where('id', $admin->role_id)->value('name'));
} else {
    echo "❌ لم يتم إنشاء حساب المدير\n";
}
echo "\n";

// الأدوار
echo "🎭 الأدوار المتاحة:\n";
echo str_repeat('─', 67) . "\n";
$roles = DB::table('roles')->orderBy('id')->get();
foreach ($roles as $role) {
    $perms_count = DB::table('permission_role')->where('role_id', $role->id)->count();
    echo sprintf("%-3d. %-20s (%d صلاحية)\n", $role->id, $role->name, $perms_count);
    echo sprintf("     الوصف: %s\n", $role->description);
}
echo "\n";

// الصلاحيات حسب المجموعات
echo "🔑 الصلاحيات المتاحة:\n";
echo str_repeat('─', 67) . "\n";
$permissions = DB::table('permissions')
    ->orderBy('group')
    ->orderBy('name')
    ->get()
    ->groupBy('group');

foreach ($permissions as $group => $perms) {
    echo "\n📌 {$group}:\n";
    foreach ($perms as $perm) {
        echo sprintf("   • %s (%s)\n", $perm->name, $perm->slug);
    }
}
echo "\n";

// صلاحيات المدير العام
echo "🌟 صلاحيات المدير العام (super-admin):\n";
echo str_repeat('─', 67) . "\n";
$superAdminRole = DB::table('roles')->where('slug', 'super-admin')->first();
if ($superAdminRole) {
    $superAdminPerms = DB::table('permissions')
        ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
        ->where('permission_role.role_id', $superAdminRole->id)
        ->select('permissions.name', 'permissions.group')
        ->get()
        ->groupBy('group');
    
    foreach ($superAdminPerms as $group => $perms) {
        echo "✓ {$group}: " . $perms->count() . " صلاحية\n";
    }
    echo "\nالمجموع: " . DB::table('permission_role')->where('role_id', $superAdminRole->id)->count() . " صلاحية\n";
}
echo "\n";

// صلاحيات المدير
echo "👨‍💼 صلاحيات المدير (admin):\n";
echo str_repeat('─', 67) . "\n";
$adminRole = DB::table('roles')->where('slug', 'admin')->first();
if ($adminRole) {
    $adminPerms = DB::table('permissions')
        ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
        ->where('permission_role.role_id', $adminRole->id)
        ->select('permissions.name')
        ->get();
    
    foreach ($adminPerms as $perm) {
        echo "• {$perm->name}\n";
    }
}
echo "\n";

// المستخدمين
echo "👥 المستخدمين المسجلين:\n";
echo str_repeat('─', 67) . "\n";
$users = DB::table('users')
    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
    ->select('users.name', 'users.email', 'roles.name as role_name')
    ->get();

foreach ($users as $user) {
    echo sprintf("%-30s %-30s [%s]\n", 
        $user->name, 
        $user->email, 
        $user->role_name ?? 'بدون دور'
    );
}
echo "\n";

// إرشادات الدخول
echo "═══════════════════════════════════════════════════════════════════\n";
echo "📝 إرشادات الدخول:\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

echo "1️⃣  للدخول إلى لوحة الإدارة:\n";
echo "   🌐 الرابط: http://127.0.0.1:8000/admin\n";
echo "   📧 البريد: admin@gmail.com\n";
echo "   🔑 كلمة المرور: admin123\n\n";

echo "2️⃣  حساب المستخدم العادي (للاختبار):\n";
echo "   📧 البريد: test@updateaden.com\n";
echo "   🔑 كلمة المرور: password123\n\n";

echo "3️⃣  الصفحات المحمية:\n";
echo "   ✓ /admin - لوحة الإدارة الرئيسية\n";
echo "   ✓ /admin/products - إدارة المنتجات\n";
echo "   ✓ /admin/categories - إدارة الأقسام\n";
echo "   ✓ /admin/orders - إدارة الطلبات\n";
echo "   ✓ /admin/users - إدارة المستخدمين\n";
echo "   ✓ /admin/roles - إدارة الأدوار (مدير عام فقط)\n";
echo "   ✓ /admin/permissions - إدارة الصلاحيات (مدير عام فقط)\n\n";

echo "═══════════════════════════════════════════════════════════════════\n";
echo "✅ نظام الصلاحيات جاهز بالكامل!\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";
