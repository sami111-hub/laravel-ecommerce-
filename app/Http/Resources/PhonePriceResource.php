<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhonePriceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'region' => $this->region,
            'currency' => $this->currency,
            'price' => $this->price,
            'formatted_price' => $this->formatted_price,
            'source' => $this->source,
            'effective_date' => $this->effective_date?->toDateString(),
            'is_current' => $this->is_current,
        ];
    }
}
