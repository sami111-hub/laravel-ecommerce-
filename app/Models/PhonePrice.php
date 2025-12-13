<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhonePrice extends Model
{
    protected $fillable = [
        'phone_id',
        'region',
        'currency',
        'price',
        'source',
        'effective_date',
        'is_current'
    ];

    protected $casts = [
        'price' => 'integer',
        'effective_date' => 'date',
        'is_current' => 'boolean'
    ];

    // Relations
    public function phone(): BelongsTo
    {
        return $this->belongsTo(Phone::class);
    }

    // Helpers
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price) . ' ' . $this->currency;
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->effective_date->format('Y-m-d');
    }
}
