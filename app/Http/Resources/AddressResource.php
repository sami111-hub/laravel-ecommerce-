<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'street' => $this->street,
            'city' => $this->city,
            'area' => $this->area,
            'building_number' => $this->building_number,
            'floor' => $this->floor,
            'apartment' => $this->apartment,
            'phone' => $this->phone,
            'additional_info' => $this->additional_info,
            'is_default' => (bool) $this->is_default,
            'full_address' => $this->full_address,
        ];
    }
}
