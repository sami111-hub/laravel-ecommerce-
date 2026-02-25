<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'model_name', 'stock', 'price_adjustment', 'is_active'];

    protected $casts = [
        'price_adjustment' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * هل الموديل متوفر في المخزون؟
     */
    public function isInStock(): bool
    {
        return $this->is_active && $this->stock > 0;
    }

    /**
     * السعر النهائي للموديل (سعر المنتج + تعديل الموديل)
     */
    public function getFinalPriceAttribute(): float
    {
        return $this->product->price + $this->price_adjustment;
    }
}
