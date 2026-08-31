<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemSnapshotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'item_id' => $this->item_id,

            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],

            'name' => $this->name,

            'code' => $this->code,

            'unit' => $this->unit,

            'description' => $this->description,

            'unit_price' => $this->unit_price,

            'minimum_stock' => $this->minimum_stock,

            'status' => $this->status,

            'change_type' => $this->change_type,

            'changed_by' => [
                'id' => $this->changedBy?->id,
                'name' => $this->changedBy?->full_name,
            ],

            'created_at' => $this->created_at,
        ];
    }
}
