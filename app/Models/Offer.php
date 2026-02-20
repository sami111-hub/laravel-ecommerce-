<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'discount_percentage',
        'start_date',
        'end_date',
        'is_active',
        'category_slug',
        'specifications',
        'custom_specifications',
        'original_price',
        'offer_price',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'specifications' => 'array',
        'custom_specifications' => 'array',
        'original_price' => 'decimal:2',
        'offer_price' => 'decimal:2',
    ];

    /**
     * الحصول على جميع المواصفات (المحددة + المخصصة)
     */
    public function getAllSpecifications(): array
    {
        $specs = $this->specifications ?? [];
        $custom = $this->custom_specifications ?? [];
        return array_merge($specs, $custom);
    }

    /**
     * حساب مبلغ التوفير
     */
    public function getSavingsAttribute()
    {
        if ($this->original_price && $this->offer_price) {
            return $this->original_price - $this->offer_price;
        }
        return null;
    }

    /**
     * العلاقة مع التصنيف
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_slug', 'slug');
    }

    // Check if offer is currently active
    public function isActive()
    {
        $now = now();
        return $this->is_active 
            && $this->start_date <= $now 
            && $this->end_date >= $now;
    }
}
