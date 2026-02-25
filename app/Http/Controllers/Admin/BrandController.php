<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::withCount('products');

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $brands = $query->latest()->paginate(15);
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands,slug',
            'description' => 'nullable|string',
            'logo' => 'nullable|url|max:500',
            'logo_file' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        // معالجة الشعار
        $logoPath = null;
        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $logoPath = $file->storeAs('brands', $filename, 'public');
            $logoPath = Storage::url($logoPath);
        } elseif (!empty($validated['logo'])) {
            $logoPath = $validated['logo'];
        }

        // توليد slug
        $slug = !empty($validated['slug']) ? $validated['slug'] : Str::slug($validated['name']);
        $counter = 1;
        $originalSlug = $slug;
        while (Brand::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        Brand::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'logo' => $logoPath,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.brands.index')->with('success', '✅ تم إضافة الماركة بنجاح');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands,slug,' . $brand->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|url|max:500',
            'logo_file' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        $logoPath = $brand->logo;
        if ($request->hasFile('logo_file')) {
            if ($brand->logo && str_starts_with($brand->logo, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $brand->logo));
            }
            $file = $request->file('logo_file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $logoPath = $file->storeAs('brands', $filename, 'public');
            $logoPath = Storage::url($logoPath);
        } elseif (!empty($validated['logo'])) {
            $logoPath = $validated['logo'];
        }

        $slug = !empty($validated['slug']) ? $validated['slug'] : Str::slug($validated['name']);
        if ($slug !== $brand->slug && Brand::where('slug', $slug)->exists()) {
            $counter = 1;
            while (Brand::where('slug', $slug)->exists()) {
                $slug = Str::slug($validated['name']) . '-' . $counter;
                $counter++;
            }
        }

        $brand->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'logo' => $logoPath,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.brands.index')->with('success', '✅ تم تحديث الماركة بنجاح');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->products()->count() > 0) {
            return redirect()->back()->with('error', 'لا يمكن حذف الماركة لأنها مرتبطة بمنتجات');
        }

        if ($brand->logo && str_starts_with($brand->logo, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $brand->logo));
        }

        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', '✅ تم حذف الماركة بنجاح');
    }
}
