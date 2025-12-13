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
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    // Check if offer is currently active
    public function isActive()
    {
        $now = now();
        return $this->is_active 
            && $this->start_date <= $now 
            && $this->end_date >= $now;
    }
}
