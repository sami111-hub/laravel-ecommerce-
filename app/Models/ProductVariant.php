<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'model_name', 'color', 'storage_size', 'ram', 'processor', 'stock', 'price_adjustment', 'is_active'];

    protected $casts = [
        'price_adjustment' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * عند تحديث الموديل: إذا وصل المخزون إلى 0 يتم حذفه تلقائياً
     */
    protected static function booted(): void
    {
        static::updated(function (ProductVariant $variant) {
            if ($variant->stock <= 0) {
                // حذف من السلات أولاً
                Cart::where('variant_id', $variant->id)->delete();
                // حذف الموديل نفسه
                $variant->delete();
            }
        });
    }

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

    /**
     * تقليل المخزون - يحذف الموديل تلقائياً عند الوصول لـ 0
     */
    public function decrementStock(int $quantity = 1): void
    {
        $this->decrement('stock', $quantity);
        $this->refresh();
        // الحذف التلقائي يتم عبر الـ observer في booted()
    }
}
