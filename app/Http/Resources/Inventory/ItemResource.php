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


            'name' => $this->name,
            'code' => $this->code,
            'unit' => $this->unit,
            'description' => $this->description,

            'current_unit_price' => $this->current_unit_price,
            'minimum_stock' => $this->minimum_stock,

            'status' => $this->status,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
