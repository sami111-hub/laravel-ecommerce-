<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'specifications', 'price', 'discount_price', 'stock', 'is_active', 'image', 'brand_id', 'sku', 'slug', 'is_flash_deal', 'flash_deal_discount', 'flash_deal_price', 'flash_deal_ends_at'];

    protected $casts = [
        'specifications' => 'array',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'flash_deal_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_flash_deal' => 'boolean',
        'flash_deal_ends_at' => 'datetime',
    ];

    /**
     * تصحيح مسار الصورة تلقائياً
     * يدعم: مسارات storage المحلية، روابط خارجية، مسارات قديمة بدون /storage/
     */
    public function getImageAttribute($value): ?string
    {
        return self::normalizeImageUrl($value);
    }

    /**
     * الحصول على مسار الصورة الخام من قاعدة البيانات (بدون معالجة)
     */
    public function getRawImagePath(): ?string
    {
        return $this->attributes['image'] ?? null;
    }

    /**
     * تصحيح مسار أي صورة
     */
    public static function normalizeImageUrl(?string $path): ?string
    {
        if (empty($path)) {
            return asset('images/no-image.svg');
        }

        // روابط خارجية - إرجاعها كما هي
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // مسار يبدأ بـ /storage/ - صحيح
        if (str_starts_with($path, '/storage/')) {
            return $path;
        }

        // مسار يبدأ بـ storage/ بدون / في البداية
        if (str_starts_with($path, 'storage/')) {
            return '/' . $path;
        }

        // مسار قديم مثل products/xxx.jpg - نضيف /storage/
        return '/storage/' . $path;
    }

    /**
     * Scope: منتجات عروض اليوم النشطة
     */
    public function scopeActiveFlashDeals($query)
    {
        return $query->where('is_flash_deal', true)
                     ->where(function ($q) {
                         $q->whereNull('flash_deal_ends_at')
                           ->orWhere('flash_deal_ends_at', '>', now());
                     });
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * صور المنتج المتعددة
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * موديلات المنتج (للإكسسوارات)
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * الموديلات المتوفرة فقط
     */
    public function availableVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)
                     ->where('is_active', true)
                     ->where('stock', '>', 0);
    }

    /**
     * جميع صور المنتج (الصورة الرئيسية + الصور الإضافية) - مسارات مصححة
     */
    public function getAllImagesAttribute(): array
    {
        // الصور الإضافية (تمر عبر accessor تلقائياً)
        $images = $this->images->pluck('image_path')->toArray();
        
        // إذا كانت هناك صورة رئيسية ولم تكن ضمن الصور المتعددة
        $mainImage = $this->image; // يمر عبر accessor تلقائياً
        if ($mainImage && !in_array($mainImage, $images)) {
            array_unshift($images, $mainImage);
        }
        
        return $images;
    }

    /**
     * هل المنتج من فئة الإكسسوارات؟
     */
    public function getIsAccessoryAttribute(): bool
    {
        $accessorySlugs = ['phone-accessories', 'cases-covers', 'accessories'];
        return $this->categories()->whereIn('slug', $accessorySlugs)->exists();
    }

    /**
     * هل المنتج من فئة الهواتف الذكية؟
     */
    public function getIsSmartphoneAttribute(): bool
    {
        $phoneSlugs = ['smartphones', 'mobiles'];
        return $this->categories()->whereIn('slug', $phoneSlugs)->exists();
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?? 0;
    }
}
