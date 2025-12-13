<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = ['user_id', 'label', 'street', 'city', 'area', 'building_number', 'floor', 'apartment', 'phone', 'additional_info', 'is_default'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullAddressAttribute()
    {
        $address = "{$this->street}, {$this->area}";
        if ($this->building_number) $address .= ", مبنى {$this->building_number}";
        if ($this->floor) $address .= ", طابق {$this->floor}";
        if ($this->apartment) $address .= ", شقة {$this->apartment}";
        return $address;
    }
}
