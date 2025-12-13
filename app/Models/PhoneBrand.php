<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PhoneBrand extends Model
{
    protected $table = 'phone_brands';
    
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    // Relations
    public function phones(): HasMany
    {
        return $this->hasMany(Phone::class, 'brand_id');
    }

    public function activePhones(): HasMany
    {
        return $this->phones()->where('is_active', true);
    }

    // Mutators
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }

    // Helpers
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }
}
