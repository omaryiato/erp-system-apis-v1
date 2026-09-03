<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],


            'name' => $this->name ?? null,
            'code' => $this->code ?? null,
            'unit' => $this->unit ?? null,
            'description' => $this->description ?? null,

            'current_unit_price' => $this->current_unit_price ?? null,
            'minimum_stock' => $this->minimum_stock ?? null,

            'status' => $this->status ?? null,

            'created_at' => $this->created_at?->format(
                'Y-m-d H:i:s'
            ),
            'updated_at' => $this->updated_at?->format(
                'Y-m-d H:i:s'
            ),
        ];
    }
}
