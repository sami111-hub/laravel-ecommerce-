<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhoneBrandResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'logo' => $this->logo ? (str_starts_with($this->logo, 'http') ? $this->logo : (($p = ltrim(preg_replace('#^/?storage#', '', $this->logo), '/')) ? asset('storage/' . $p) : null)) : null,
            'description' => $this->description,
            'phones_count' => $this->when(isset($this->phones_count), $this->phones_count),
        ];
    }
}
