<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $fillable = ['code', 'type', 'value', 'min_order', 'usage_limit', 'usage_count', 'starts_at', 'ends_at', 'is_active'];

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        
        if ($this->starts_at && now() < $this->starts_at) return false;
        if ($this->ends_at && now() > $this->ends_at) return false;
        
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) return false;
        
        return true;
    }

    public function applyTo(float $amount): float
    {
        if (!$this->isValid()) return $amount;
        
        if ($this->min_order && $amount < $this->min_order) return $amount;
        
        if ($this->type === 'percentage') {
            return $amount - ($amount * $this->value / 100);
        }
        
        return max(0, $amount - $this->value);
    }

    public function getDiscountAmount(float $amount): float
    {
        if (!$this->isValid()) return 0;
        
        if ($this->min_order && $amount < $this->min_order) return 0;
        
        if ($this->type === 'percentage') {
            return $amount * $this->value / 100;
        }
        
        return min($this->value, $amount);
    }
}
