<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'supplier_code' => $this->supplier_code,

            'name' => $this->name,

            'phone' => $this->phone,

            'email' => $this->email,

            'address' => $this->address,

            'tax_number' => $this->tax_number,

            'notes' => $this->notes,

            'status' => $this->status,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}
