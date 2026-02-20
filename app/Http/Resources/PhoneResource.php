<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhoneResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail ? (str_starts_with($this->thumbnail, 'http') ? $this->thumbnail : asset('storage/' . $this->thumbnail)) : null,
            'images' => $this->images_url,
            'chipset' => $this->chipset,
            'ram' => $this->ram,
            'storage' => $this->storage,
            'display_size' => (float) $this->display_size,
            'battery_mah' => $this->battery_mah,
            'os' => $this->os,
            'release_year' => $this->release_year,
            'description' => $this->description,
            'views' => $this->views,
            'brand' => new PhoneBrandResource($this->whenLoaded('brand')),
            'specs' => PhoneSpecResource::collection($this->whenLoaded('specs')),
            'prices' => PhonePriceResource::collection($this->whenLoaded('prices')),
            'current_price' => $this->when($this->relationLoaded('prices'), function () {
                $current = $this->prices->where('is_current', true)->sortByDesc('effective_date')->first();
                return $current ? new PhonePriceResource($current) : null;
            }),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
