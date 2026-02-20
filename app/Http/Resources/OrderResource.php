<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'total' => (float) $this->total,
            'subtotal' => (float) $this->subtotal,
            'discount' => (float) $this->discount,
            'coupon_code' => $this->coupon_code,
            'tracking_code' => $this->tracking_code,
            'shipping_address' => $this->shipping_address,
            'phone' => $this->phone,
            'notes' => $this->notes,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
