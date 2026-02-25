<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // Products
            ['name' => 'عرض المنتجات', 'slug' => 'view-products', 'group' => 'products'],
            ['name' => 'إنشاء منتجات', 'slug' => 'create-products', 'group' => 'products'],
            ['name' => 'تعديل منتجات', 'slug' => 'edit-products', 'group' => 'products'],
            ['name' => 'حذف منتجات', 'slug' => 'delete-products', 'group' => 'products'],
            
            // Orders
            ['name' => 'عرض الطلبات', 'slug' => 'view-orders', 'group' => 'orders'],
            ['name' => 'تعديل الطلبات', 'slug' => 'edit-orders', 'group' => 'orders'],
            ['name' => 'حذف الطلبات', 'slug' => 'delete-orders', 'group' => 'orders'],
            
            // Users
            ['name' => 'عرض المستخدمين', 'slug' => 'view-users', 'group' => 'users'],
            ['name' => 'إنشاء مستخدمين', 'slug' => 'create-users', 'group' => 'users'],
            ['name' => 'تعديل مستخدمين', 'slug' => 'edit-users', 'group' => 'users'],
            ['name' => 'حذف مستخدمين', 'slug' => 'delete-users', 'group' => 'users'],
            
            // Roles
            ['name' => 'عرض الأدوار', 'slug' => 'view-roles', 'group' => 'roles'],
            ['name' => 'إنشاء أدوار', 'slug' => 'create-roles', 'group' => 'roles'],
            ['name' => 'تعديل أدوار', 'slug' => 'edit-roles', 'group' => 'roles'],
            ['name' => 'حذف أدوار', 'slug' => 'delete-roles', 'group' => 'roles'],
            
            // Permissions
            ['name' => 'عرض الصلاحيات', 'slug' => 'view-permissions', 'group' => 'permissions'],
            ['name' => 'إنشاء صلاحيات', 'slug' => 'create-permissions', 'group' => 'permissions'],
            ['name' => 'تعديل صلاحيات', 'slug' => 'edit-permissions', 'group' => 'permissions'],
            ['name' => 'حذف صلاحيات', 'slug' => 'delete-permissions', 'group' => 'permissions'],
            
            // Categories
            ['name' => 'عرض التصنيفات', 'slug' => 'view-categories', 'group' => 'categories'],
            ['name' => 'إنشاء تصنيفات', 'slug' => 'create-categories', 'group' => 'categories'],
            ['name' => 'تعديل تصنيفات', 'slug' => 'edit-categories', 'group' => 'categories'],
            ['name' => 'حذف تصنيفات', 'slug' => 'delete-categories', 'group' => 'categories'],
            
            // Settings
            ['name' => 'إعدادات النظام', 'slug' => 'manage-settings', 'group' => 'settings'],
            
            // Brands - إدارة الماركات (مدير النظام فقط)
            ['name' => 'إدارة الماركات', 'slug' => 'manage-brands', 'group' => 'brands'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(
                ['slug' => $perm['slug']],
                [
                    'name' => $perm['name'],
                    'group' => $perm['group'],
                    'is_active' => true,
                ]
            );
        }

        // Create Roles
        $superAdminRole = Role::updateOrCreate(
            ['slug' => 'super-admin'],
            [
                'name' => 'مدير عام',
                'description' => 'صلاحيات كاملة على النظام',
                'is_active' => true,
            ]
        );

        $adminRole = Role::updateOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'مدير',
                'description' => 'إدارة المنتجات والطلبات',
                'is_active' => true,
            ]
        );

        $managerRole = Role::updateOrCreate(
            ['slug' => 'manager'],
            [
                'name' => 'مسؤول',
                'description' => 'إدارة الطلبات والمنتجات',
                'is_active' => true,
            ]
        );

        $userRole = Role::updateOrCreate(
            ['slug' => 'user'],
            [
                'name' => 'مستخدم',
                'description' => 'صلاحيات المستخدم العادي',
                'is_active' => true,
            ]
        );

        // Assign all permissions to super admin
        $superAdminRole->permissions()->sync(Permission::pluck('id'));

        // Assign permissions to admin
        $adminPermissions = Permission::whereIn('slug', [
            'view-products', 'create-products', 'edit-products', 'delete-products',
            'view-orders', 'edit-orders',
            'view-users', 'create-users', 'edit-users',
            'view-categories', 'create-categories', 'edit-categories', 'delete-categories',
        ])->pluck('id');
        $adminRole->permissions()->sync($adminPermissions);

        // Assign permissions to manager
        $managerPermissions = Permission::whereIn('slug', [
            'view-products', 'view-orders', 'edit-orders',
            'view-users', 'view-categories',
        ])->pluck('id');
        $managerRole->permissions()->sync($managerPermissions);

        // Create Super Admin User for Update Aden System
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'مدير النظام',
                'password' => Hash::make('admin123'),
                'role_id' => $superAdminRole->id,
            ]
        );
        $superAdmin->roles()->syncWithoutDetaching([$superAdminRole->id]);

        $this->command->info('✅ تم إنشاء الأدوار والصلاحيات بنجاح!');
        $this->command->info('👤 حساب مدير النظام (أبديت تكنولوجي):');
        $this->command->info('   البريد: admin@gmail.com');
        $this->command->info('   كلمة المرور: admin123');
    }
}
