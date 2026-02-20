<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'specifications' => $this->specifications,
            'price' => (float) $this->price,
            'discount_price' => $this->discount_price ? (float) $this->discount_price : null,
            'stock' => $this->stock,
            'is_active' => $this->is_active,
            'image' => $this->image ? (str_starts_with($this->image, 'http') ? $this->image : asset('storage/' . $this->image)) : null,
            'sku' => $this->sku,
            'is_flash_deal' => $this->is_flash_deal,
            'flash_deal_price' => $this->flash_deal_price ? (float) $this->flash_deal_price : null,
            'flash_deal_ends_at' => $this->flash_deal_ends_at?->toDateTimeString(),
            'average_rating' => round($this->average_rating, 1),
            'reviews_count' => $this->when($this->relationLoaded('reviews'), fn() => $this->reviews->count(), 0),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
