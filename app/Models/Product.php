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
     * جميع صور المنتج (الصورة الرئيسية + الصور الإضافية)
     */
    public function getAllImagesAttribute(): array
    {
        $images = $this->images->pluck('image_path')->toArray();
        
        // إذا كانت هناك صورة رئيسية قديمة ولم تكن ضمن الصور المتعددة
        if ($this->image && !in_array($this->image, $images)) {
            array_unshift($images, $this->image);
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
