<?php
/**
 * Reset admin login - creates/updates admin user and assigns roles
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== RESET ADMIN LOGIN ===\n\n";

// 1. List existing users
echo "Current users:\n";
$users = User::all();
foreach ($users as $u) {
    echo "  ID:{$u->id} | {$u->name} | {$u->email}\n";
}
echo "Total: " . $users->count() . " users\n\n";

// 2. Create or update admin user
$adminEmail = 'admin@update-aden.com';
$adminPassword = 'Admin@12345';

$admin = User::where('email', $adminEmail)->first();
if ($admin) {
    $admin->password = Hash::make($adminPassword);
    $admin->save();
    echo "UPDATED: {$adminEmail} password reset to: {$adminPassword}\n";
} else {
    $admin = User::create([
        'name' => 'Admin',
        'email' => $adminEmail,
        'password' => Hash::make($adminPassword),
    ]);
    echo "CREATED: {$adminEmail} with password: {$adminPassword}\n";
}

// 3. Setup roles if Role model exists
try {
    $roleClass = 'App\\Models\\Role';
    if (class_exists($roleClass)) {
        // Create super-admin role if not exists
        $superAdmin = DB::table('roles')->where('name', 'super-admin')->first();
        if (!$superAdmin) {
            $superAdminId = DB::table('roles')->insertGetId([
                'name' => 'super-admin',
                'slug' => 'super-admin',
                'description' => 'Super Administrator',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "Created super-admin role (ID: {$superAdminId})\n";
        } else {
            $superAdminId = $superAdmin->id;
            echo "super-admin role exists (ID: {$superAdminId})\n";
        }

        // Assign role to admin user
        $hasRole = DB::table('role_user')
            ->where('user_id', $admin->id)
            ->where('role_id', $superAdminId)
            ->exists();

        if (!$hasRole) {
            DB::table('role_user')->insert([
                'user_id' => $admin->id,
                'role_id' => $superAdminId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "Assigned super-admin role to {$adminEmail}\n";
        } else {
            echo "Admin already has super-admin role\n";
        }

        // Assign ALL permissions to super-admin role
        $permissions = DB::table('permissions')->where('is_active', 1)->pluck('id');
        if ($permissions->count() > 0) {
            DB::table('permission_role')->where('role_id', $superAdminId)->delete();
            foreach ($permissions as $permId) {
                DB::table('permission_role')->insert([
                    'role_id' => $superAdminId,
                    'permission_id' => $permId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            echo "Assigned {$permissions->count()} permissions to super-admin role\n";
        }
    }
} catch (\Exception $e) {
    echo "Role setup note: " . $e->getMessage() . "\n";
}

// 4. Also reset first user's password if different from admin
$firstUser = User::orderBy('id')->first();
if ($firstUser && $firstUser->id !== $admin->id) {
    $firstUser->password = Hash::make($adminPassword);
    $firstUser->save();
    echo "Also reset first user ({$firstUser->email}) password to: {$adminPassword}\n";
    
    // Assign super-admin role to first user too
    try {
        $superAdmin = DB::table('roles')->where('name', 'super-admin')->first();
        if ($superAdmin) {
            $hasRole = DB::table('role_user')
                ->where('user_id', $firstUser->id)
                ->where('role_id', $superAdmin->id)
                ->exists();
            if (!$hasRole) {
                DB::table('role_user')->insert([
                    'user_id' => $firstUser->id,
                    'role_id' => $superAdmin->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                echo "Assigned super-admin role to {$firstUser->email}\n";
            }
        }
    } catch (\Exception $e) {
        // ignore
    }
}

echo "\n=== LOGIN CREDENTIALS ===\n";
echo "Email: {$adminEmail}\n";
echo "Password: {$adminPassword}\n";
echo "URL: https://store.update-aden.com/login\n";
echo "=========================\n";
echo "DONE!\n";
