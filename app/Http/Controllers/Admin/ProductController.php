<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
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
            'specifications' => 'nullable|array',
            'specifications.*' => 'nullable|string|max:500',
            'custom_spec_keys' => 'nullable|array',
            'custom_spec_keys.*' => 'nullable|string|max:255',
            'custom_spec_values' => 'nullable|array',
            'custom_spec_values.*' => 'nullable|string|max:500',
            // صور متعددة
            'additional_images' => 'nullable|array|max:10',
            'additional_images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'additional_image_urls' => 'nullable|array|max:10',
            'additional_image_urls.*' => 'nullable|url|max:500',
            // موديلات المنتج
            'variant_names' => 'nullable|array',
            'variant_names.*' => 'nullable|string|max:255',
            'variant_stocks' => 'nullable|array',
            'variant_stocks.*' => 'nullable|integer|min:0',
            'variant_prices' => 'nullable|array',
            'variant_prices.*' => 'nullable|numeric',
            'variant_colors' => 'nullable|array',
            'variant_colors.*' => 'nullable|string|max:255',
            'variant_storages' => 'nullable|array',
            'variant_storages.*' => 'nullable|string|max:255',
            'variant_rams' => 'nullable|array',
            'variant_rams.*' => 'nullable|string|max:255',
            'variant_processors' => 'nullable|array',
            'variant_processors.*' => 'nullable|string|max:255',
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

        // تجميع المواصفات
        $specifications = $this->buildSpecifications($request);

        // إنشاء المنتج
        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'specifications' => $specifications,
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

        // حفظ الصور المتعددة
        $this->saveProductImages($request, $product);

        // حفظ الموديلات (للإكسسوارات)
        $this->saveProductVariants($request, $product);

        return redirect()
            ->route('admin.products.index')
            ->with('success', '✅ تم إضافة المنتج بنجاح');
    }

    public function show(Product $product)
    {
        $product->load(['brand', 'categories', 'reviews.user', 'images', 'variants']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $brands = Brand::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();
        $product->load(['categories', 'images', 'variants']);
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
            'specifications' => 'nullable|array',
            'specifications.*' => 'nullable|string|max:500',
            'custom_spec_keys' => 'nullable|array',
            'custom_spec_keys.*' => 'nullable|string|max:255',
            'custom_spec_values' => 'nullable|array',
            'custom_spec_values.*' => 'nullable|string|max:500',
            // صور متعددة
            'additional_images' => 'nullable|array|max:10',
            'additional_images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'additional_image_urls' => 'nullable|array|max:10',
            'additional_image_urls.*' => 'nullable|url|max:500',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer|exists:product_images,id',
            // موديلات الإكسسوارات
            'variant_names' => 'nullable|array',
            'variant_names.*' => 'nullable|string|max:255',
            'variant_stocks' => 'nullable|array',
            'variant_stocks.*' => 'nullable|integer|min:0',
            'variant_prices' => 'nullable|array',
            'variant_prices.*' => 'nullable|numeric',
            'variant_colors' => 'nullable|array',
            'variant_colors.*' => 'nullable|string|max:255',
            'variant_storages' => 'nullable|array',
            'variant_storages.*' => 'nullable|string|max:255',
            'variant_rams' => 'nullable|array',
            'variant_rams.*' => 'nullable|string|max:255',
            'variant_processors' => 'nullable|array',
            'variant_processors.*' => 'nullable|string|max:255',
            'delete_variants' => 'nullable|array',
            'delete_variants.*' => 'integer|exists:product_variants,id',
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

        // تجميع المواصفات
        $specifications = $this->buildSpecifications($request);

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'specifications' => $specifications,
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

        // حذف الصور المحددة
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $img = ProductImage::find($imageId);
                if ($img && $img->product_id === $product->id) {
                    if (str_starts_with($img->image_path, '/storage/')) {
                        Storage::disk('public')->delete(str_replace('/storage/', '', $img->image_path));
                    }
                    $img->delete();
                }
            }
        }

        // حفظ الصور المتعددة الجديدة
        $this->saveProductImages($request, $product);

        // حذف الموديلات المحددة
        if ($request->has('delete_variants')) {
            ProductVariant::whereIn('id', $request->delete_variants)
                ->where('product_id', $product->id)
                ->delete();
        }

        // تحديث الموديلات الحالية
        if ($request->has('existing_variant_ids')) {
            $ids = $request->input('existing_variant_ids', []);
            $names = $request->input('existing_variant_names', []);
            $stocks = $request->input('existing_variant_stocks', []);
            $prices = $request->input('existing_variant_prices', []);
            $actives = $request->input('existing_variant_active', []);
            $colors = $request->input('existing_variant_colors', []);
            $storages = $request->input('existing_variant_storages', []);
            $rams = $request->input('existing_variant_rams', []);
            $processors = $request->input('existing_variant_processors', []);
            
            foreach ($ids as $i => $id) {
                $variant = ProductVariant::where('id', $id)->where('product_id', $product->id)->first();
                if ($variant && !in_array($id, $request->input('delete_variants', []))) {
                    $variant->update([
                        'model_name' => trim($names[$i] ?? $variant->model_name),
                        'stock' => intval($stocks[$i] ?? $variant->stock),
                        'price_adjustment' => floatval($prices[$i] ?? $variant->price_adjustment),
                        'is_active' => boolval($actives[$i] ?? true),
                        'color' => trim($colors[$i] ?? '') ?: null,
                        'storage_size' => trim($storages[$i] ?? '') ?: null,
                        'ram' => trim($rams[$i] ?? '') ?: null,
                        'processor' => trim($processors[$i] ?? '') ?: null,
                    ]);
                }
            }
        }

        // تحديث/إضافة الموديلات الجديدة
        $this->saveProductVariants($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        // حذف الصور المرفوعة
        foreach ($product->images as $img) {
            if (str_starts_with($img->image_path, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $img->image_path));
            }
        }
        
        $product->categories()->detach();
        $product->images()->delete();
        $product->variants()->delete();
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'تم حذف المنتج بنجاح');
    }

    /**
     * تجميع المواصفات من حقول النموذج (الحقول المحددة + المخصصة)
     */
    private function buildSpecifications(Request $request): ?array
    {
        $specifications = [];

        // المواصفات من حقول التصنيف
        if ($request->has('specifications') && is_array($request->specifications)) {
            foreach ($request->specifications as $key => $value) {
                if (!empty(trim($value))) {
                    $specifications[$key] = trim($value);
                }
            }
        }

        // المواصفات المخصصة
        $customKeys = $request->input('custom_spec_keys', []);
        $customValues = $request->input('custom_spec_values', []);

        if (is_array($customKeys) && is_array($customValues)) {
            foreach ($customKeys as $index => $key) {
                $key = trim($key ?? '');
                $value = trim($customValues[$index] ?? '');
                if (!empty($key) && !empty($value)) {
                    $specifications[$key] = $value;
                }
            }
        }

        return !empty($specifications) ? $specifications : null;
    }

    /**
     * حفظ الصور المتعددة للمنتج
     */
    private function saveProductImages(Request $request, Product $product): void
    {
        $sortOrder = $product->images()->max('sort_order') ?? 0;

        // صور مرفوعة من الجهاز
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $imageFile) {
                if ($imageFile && $imageFile->isValid()) {
                    $sortOrder++;
                    $filename = time() . '_' . $sortOrder . '_' . Str::slug(pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $imageFile->getClientOriginalExtension();
                    $path = $imageFile->storeAs('products', $filename, 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => Storage::url($path),
                        'sort_order' => $sortOrder,
                        'is_primary' => false,
                    ]);
                }
            }
        }

        // صور من روابط URL
        $imageUrls = $request->input('additional_image_urls', []);
        if (is_array($imageUrls)) {
            foreach ($imageUrls as $url) {
                $url = trim($url ?? '');
                if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
                    $sortOrder++;
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $url,
                        'sort_order' => $sortOrder,
                        'is_primary' => false,
                    ]);
                }
            }
        }
    }

    /**
     * حفظ موديلات المنتج (للإكسسوارات)
     */
    private function saveProductVariants(Request $request, Product $product): void
    {
        $names = $request->input('variant_names', []);
        $stocks = $request->input('variant_stocks', []);
        $prices = $request->input('variant_prices', []);
        $colors = $request->input('variant_colors', []);
        $storages = $request->input('variant_storages', []);
        $rams = $request->input('variant_rams', []);
        $processors = $request->input('variant_processors', []);

        if (!is_array($names)) return;

        foreach ($names as $index => $name) {
            $name = trim($name ?? '');
            if (empty($name)) continue;

            $stock = intval($stocks[$index] ?? 0);
            $priceAdj = floatval($prices[$index] ?? 0);
            $color = trim($colors[$index] ?? '') ?: null;
            $storage = trim($storages[$index] ?? '') ?: null;
            $ram = trim($rams[$index] ?? '') ?: null;
            $processor = trim($processors[$index] ?? '') ?: null;

            // تحديث أو إنشاء الموديل
            ProductVariant::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'model_name' => $name,
                ],
                [
                    'stock' => $stock,
                    'price_adjustment' => $priceAdj,
                    'color' => $color,
                    'storage_size' => $storage,
                    'ram' => $ram,
                    'processor' => $processor,
                    'is_active' => true,
                ]
            );
        }
    }
}
