<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\SiteSetting;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // جلب أسعار الصرف من الإعدادات
        $sarRate = floatval(SiteSetting::get('exchange_rate_sar', '3.75'));
        $yerRate = floatval(SiteSetting::get('exchange_rate_yer', '535'));
        
        // السعر الأساسي (بالدولار)
        $priceUsd = (float) $this->price;
        $discountPriceUsd = $this->discount_price ? (float) $this->discount_price : null;
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'specifications' => is_array($this->specifications)
                ? $this->specifications
                : (json_decode($this->specifications, true) ?? []),
            // الأسعار بالعملات الثلاث
            'price' => $priceUsd,
            'price_usd' => $priceUsd,
            'price_sar' => round($priceUsd * $sarRate, 2),
            'price_yer' => round($priceUsd * $yerRate),
            'discount_price' => $discountPriceUsd,
            'discount_price_usd' => $discountPriceUsd,
            'discount_price_sar' => $discountPriceUsd ? round($discountPriceUsd * $sarRate, 2) : null,
            'discount_price_yer' => $discountPriceUsd ? round($discountPriceUsd * $yerRate) : null,
            // أسعار الصرف (للتطبيق)
            'exchange_rates' => [
                'sar' => $sarRate,
                'yer' => $yerRate,
            ],
            'stock' => $this->stock,
            'is_active' => $this->is_active,
            'is_featured' => (bool) $this->is_featured,
            'image' => $this->image
                ? (str_starts_with($this->image, 'http')
                    ? $this->image
                    : (($p = ltrim(preg_replace('#^/?storage#', '', $this->image), '/')) ? asset('storage/' . $p) : null))
                : null,
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
