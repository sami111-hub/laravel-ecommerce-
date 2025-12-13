<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'categories']);

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Filter by brand
        if ($request->has('brand') && $request->brand) {
            $query->where('brand_id', $request->brand);
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $brands = Brand::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('brands', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|url|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => 'nullable|string|max:255|unique:products,sku',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // معالجة الصورة
        $imagePath = null;
        
        // إذا تم رفع صورة من الجهاز
        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file');
            $filename = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $filename, 'public');
            $imagePath = Storage::url($imagePath);
        }
        // إذا تم إدخال رابط URL
        elseif (!empty($validated['image'])) {
            $imagePath = $validated['image'];
        }

        // توليد slug فريد
        $slug = Str::slug($validated['name']);
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = Str::slug($validated['name']) . '-' . $counter;
            $counter++;
        }

        // توليد SKU تلقائياً إذا لم يتم إدخاله
        if (empty($validated['sku'])) {
            $sku = 'PRD-' . strtoupper(Str::random(8));
            $counter = 1;
            while (Product::where('sku', $sku)->exists()) {
                $sku = 'PRD-' . strtoupper(Str::random(8));
                $counter++;
                if ($counter > 10) break; // تجنب الحلقة اللانهائية
            }
            $validated['sku'] = $sku;
        }

        // إنشاء المنتج
        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'image' => $imagePath,
            'brand_id' => $validated['brand_id'] ?? null,
            'sku' => $validated['sku'],
            'slug' => $slug,
        ]);

        // ربط التصنيفات
        if ($request->has('categories') && !empty($validated['categories'])) {
            $product->categories()->attach($validated['categories']);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', '✅ تم إضافة المنتج بنجاح');
    }

    public function show(Product $product)
    {
        $product->load(['brand', 'categories', 'reviews.user']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $brands = Brand::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();
        $product->load('categories');
        return view('admin.products.edit', compact('product', 'brands', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|url|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => 'nullable|string|max:255|unique:products,sku,' . $product->id,
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // معالجة الصورة
        $imagePath = $product->image; // الاحتفاظ بالصورة الحالية افتراضياً
        
        // إذا تم رفع صورة من الجهاز
        if ($request->hasFile('image_file')) {
            // حذف الصورة القديمة إذا كانت محلية
            if ($product->image && str_starts_with($product->image, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $product->image));
            }
            
            $image = $request->file('image_file');
            $filename = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $filename, 'public');
            $imagePath = Storage::url($imagePath);
        }
        // إذا تم إدخال رابط URL جديد
        elseif (!empty($validated['image'])) {
            // حذف الصورة القديمة إذا كانت محلية
            if ($product->image && str_starts_with($product->image, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $product->image));
            }
            $imagePath = $validated['image'];
        }

        $slug = Str::slug($validated['name']);
        if ($slug !== $product->slug && Product::where('slug', $slug)->exists()) {
            $counter = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = Str::slug($validated['name']) . '-' . $counter;
                $counter++;
            }
        }

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'image' => $imagePath,
            'brand_id' => $validated['brand_id'] ?? null,
            'sku' => $validated['sku'] ?? null,
            'slug' => $slug !== $product->slug ? $slug : $product->slug,
        ]);

        if ($request->has('categories')) {
            $product->categories()->sync($validated['categories']);
        } else {
            $product->categories()->detach();
        }

        return redirect()->route('admin.products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        $product->categories()->detach();
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'تم حذف المنتج بنجاح');
    }
}
