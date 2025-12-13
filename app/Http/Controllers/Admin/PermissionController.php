<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::latest()->get()->groupBy('group');
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        $groups = [
            'products' => 'المنتجات',
            'orders' => 'الطلبات',
            'users' => 'المستخدمين',
            'roles' => 'الأدوار',
            'permissions' => 'الصلاحيات',
            'categories' => 'التصنيفات',
            'settings' => 'الإعدادات',
        ];
        return view('admin.permissions.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'group' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Permission::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'group' => $validated['group'],
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'تم إنشاء الصلاحية بنجاح');
    }

    public function show(Permission $permission)
    {
        $permission->load('roles');
        return view('admin.permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        $groups = [
            'products' => 'المنتجات',
            'orders' => 'الطلبات',
            'users' => 'المستخدمين',
            'roles' => 'الأدوار',
            'permissions' => 'الصلاحيات',
            'categories' => 'التصنيفات',
            'settings' => 'الإعدادات',
        ];
        return view('admin.permissions.edit', compact('permission', 'groups'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'group' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $permission->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'group' => $validated['group'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active') ? $validated['is_active'] : $permission->is_active,
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'تم تحديث الصلاحية بنجاح');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'تم حذف الصلاحية بنجاح');
    }
}
