<?php

namespace App\Http\Resources\Asset;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_code' => $this->category_code,
            'category_name' => $this->category_name,
            'description' => $this->description,
            'is_active' => $this->is_active,

            'assets_count' => $this->whenCounted('assets'),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
