<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? null,
            'description' => $this->description ?? null,
            'status' => $this->status ?? null,
            'items' => ItemResource::collection($this->whenLoaded('items')) ?? null,
            'created_at' => $this->created_at?->format(
                'Y-m-d H:i:s'
            ),
            'updated_at' => $this->updated_at?->format(
                'Y-m-d H:i:s'
            ),
        ];
    }
}
