<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image ? (str_starts_with($this->image, 'http') ? $this->image : asset('storage/' . $this->image)) : null,
            'discount_percentage' => $this->discount_percentage,
            'original_price' => $this->original_price ? (float) $this->original_price : null,
            'offer_price' => $this->offer_price ? (float) $this->offer_price : null,
            'savings' => $this->savings ? (float) $this->savings : null,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'is_active' => $this->isActive(),
            'category_slug' => $this->category_slug,
            'specifications' => $this->getAllSpecifications(),
        ];
    }
}
