<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Phone extends Model
{
    protected $fillable = [
        'brand_id',
        'name',
        'slug',
        'thumbnail',
        'images',
        'chipset',
        'ram',
        'storage',
        'display_size',
        'battery_mah',
        'os',
        'release_year',
        'description',
        'is_active',
        'views'
    ];

    protected $casts = [
        'images' => 'array',
        'display_size' => 'decimal:2',
        'battery_mah' => 'integer',
        'release_year' => 'integer',
        'is_active' => 'boolean',
        'views' => 'integer'
    ];

    // Relations
    public function brand(): BelongsTo
    {
        return $this->belongsTo(PhoneBrand::class, 'brand_id');
    }

    public function specs(): HasMany
    {
        return $this->hasMany(PhoneSpec::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(PhonePrice::class);
    }

    public function currentPrice(): HasMany
    {
        return $this->prices()->where('is_current', true)->latest('effective_date');
    }

    // Mutators
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($phone) {
            if (empty($phone->slug)) {
                $phone->slug = Str::slug($phone->brand->name . '-' . $phone->name);
            }
        });
    }

    // Helpers
    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : asset('images/placeholder-phone.png');
    }

    public function getImagesUrlAttribute(): array
    {
        if (!$this->images) return [];
        
        return array_map(function($image) {
            return asset('storage/' . $image);
        }, $this->images);
    }

    public function getCurrentPriceAttribute()
    {
        return $this->currentPrice()->first();
    }

    public function getSpecsByGroup(string $group)
    {
        return $this->specs()->where('group', $group)->orderBy('order')->get();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByBrand($query, $brandSlug)
    {
        return $query->whereHas('brand', function($q) use ($brandSlug) {
            $q->where('slug', $brandSlug);
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('chipset', 'like', "%{$search}%")
              ->orWhereHas('brand', function($qb) use ($search) {
                  $qb->where('name', 'like', "%{$search}%");
              });
        });
    }
}
