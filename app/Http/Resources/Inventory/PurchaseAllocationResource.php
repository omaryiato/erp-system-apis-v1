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
                'id' => $this->project?->id,
                'name' => $this->project?->project_name,
                'code' => $this->project?->project_code,
            ],

            'quantity' => $this->quantity,

            'notes' => $this->notes,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}
