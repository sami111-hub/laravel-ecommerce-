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

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?? 0;
    }
}
