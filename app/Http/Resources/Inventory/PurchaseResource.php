<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'supplier' => [
                'id' => $this->supplier?->id,
                'name' => $this->supplier?->name,
            ],

            'purchase_date' => $this->purchase_date,

            'reference_number' => $this->reference_number,

            'status' => $this->status,

            'items' => PurchaseItemResource::collection(
                $this->whenLoaded('items')
            ),

            'total_amount' => $this->when(
                $this->relationLoaded('items'),
                fn () => $this->items->sum(
                    fn ($item) => $item->total_amount
                )
            ),

            'notes' => $this->notes,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}
