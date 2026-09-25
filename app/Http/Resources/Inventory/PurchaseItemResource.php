<?php

namespace App\Http\Resources\Inventory;

use App\Http\Resources\ChequeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $allocated = $this->relationLoaded('allocations')
            ? $this->allocations->sum('quantity')
            : null;

        return [
            'id' => $this->id,

            'item' => [
                'id' => $this->item?->id,
                'name' => $this->item?->name,
                'code' => $this->item?->code,
            ],

            'quantity' => $this->quantity,

            'unit_price' => $this->unit_price,

            'purchase_type' => $this->purchase_type,

            'total_amount' => $this->total_amount,

            'allocated_quantity' => $allocated,

            'remaining_quantity' => $allocated !== null
                ? $this->quantity - $allocated
                : null,

            'allocations' => PurchaseAllocationResource::collection(
                $this->whenLoaded('allocations')
            ),

            'cheques' => ChequeResource::collection(
                $this->whenLoaded('cheques')
            ),

            'notes' => $this->notes,
        ];
    }
}
