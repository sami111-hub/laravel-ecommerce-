<?php
/**
 * 🔧 إصلاح مشكلة المستخدم المدير والأدوار
 */

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

echo "\n🔧 إصلاح المستخدم المدير والأدوار\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// عرض الأدوار الحالية
echo "📋 الأدوار الحالية:\n";
foreach (Role::all() as $role) {
    echo "   - ID: {$role->id}, Name: '{$role->name}', Slug: '{$role->slug}'\n";
}

// تحقق من وجود الأدوار الأساسية (بحسب name أو slug)
echo "\n📋 التحقق من الأدوار الأساسية:\n";
$requiredRoles = [
    ['name' => 'super-admin', 'slug' => 'super-admin', 'description' => 'مدير عام بجميع الصلاحيات'],
    ['name' => 'admin', 'slug' => 'admin', 'description' => 'مدير بصلاحيات إدارية'],
];

foreach ($requiredRoles as $roleData) {
    $role = Role::where('name', $roleData['name'])
                ->orWhere('slug', $roleData['slug'])
                ->first();
    
    if (!$role) {
        echo "   ⚠️ الدور '{$roleData['name']}' غير موجود - جاري الإنشاء...\n";
        $role = Role::create([
            'name' => $roleData['name'],
            'slug' => $roleData['slug'],
            'description' => $roleData['description'],
            'is_active' => true,
        ]);
        echo "   ✅ تم إنشاء الدور '{$roleData['name']}'\n";
    } else {
        echo "   ✅ الدور '{$roleData['name']}' موجود (ID: {$role->id})\n";
    }
}

// البحث عن دور المدير العام بأي اسم
$superAdminRole = Role::where('name', 'super-admin')
    ->orWhere('slug', 'super-admin')
    ->orWhere('name', 'مدير عام')
    ->first();

if (!$superAdminRole) {
    // استخدام أول دور متاح
    $superAdminRole = Role::first();
    echo "   ℹ️ استخدام الدور الأول: {$superAdminRole->name}\n";
}

// 2. التحقق من المستخدم الأول وإعطائه صلاحيات المدير
echo "\n👤 إصلاح صلاحيات المستخدم:\n";
$firstUser = User::first();

if ($firstUser) {
    echo "   ℹ️ المستخدم الأول: {$firstUser->name} ({$firstUser->email})\n";
    
    if ($superAdminRole) {
        // التحقق من عدم وجود الدور مسبقاً
        $hasRole = $firstUser->roles()->where('roles.id', $superAdminRole->id)->exists();
        
        if (!$hasRole) {
            $firstUser->roles()->attach($superAdminRole->id);
            echo "   ✅ تم إضافة دور '{$superAdminRole->name}' للمستخدم {$firstUser->name}\n";
        } else {
            echo "   ✅ المستخدم {$firstUser->name} لديه دور '{$superAdminRole->name}' مسبقاً\n";
        }
    }
    
    // عرض جميع أدوار المستخدم
    $userRoles = $firstUser->roles()->pluck('name')->toArray();
    echo "   📋 أدوار المستخدم: " . implode(', ', $userRoles) . "\n";
    
    // إضافة جميع الصلاحيات للدور الأول
    if ($superAdminRole) {
        $allPermissions = Permission::where('is_active', true)->pluck('id');
        $superAdminRole->permissions()->sync($allPermissions);
        echo "   ✅ تم تعيين جميع الصلاحيات ({$allPermissions->count()}) للدور '{$superAdminRole->name}'\n";
    }
}

echo "\n✅ تم الإصلاح بنجاح!\n\n";
