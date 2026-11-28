<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseAllocationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'project' => [
                'id' => $this->project?->id ?? null,
                'name' => $this->project?->project_name ?? null,
                'code' => $this->project?->project_code ?? null,
            ],

            'quantity' => $this->quantity,

            'notes' => $this->notes,

            'created_at' => $this->created_at?->format('Y-m-d H:i:s') ?? null,

            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s') ?? null,
        ];
    }
}
