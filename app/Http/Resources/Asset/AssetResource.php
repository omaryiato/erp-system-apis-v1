<?php

namespace App\Http\Resources\Asset;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $maintenanceTotal = $this->maintenances?->sum('cost') ?? 0;

        $expenseTotal = $this->expenses?->sum('amount') ?? 0;


        return [
            'id' => $this->id,

            'asset_code' => $this->asset_code,
            'asset_name' => $this->asset_name,

            'description' => $this->description,

            'serial_number' => $this->serial_number,
            'model' => $this->model,
            'manufacturer' => $this->manufacturer,

            'purchase_date' => $this->purchase_date?->format('Y-m-d'),
            'purchase_cost' => $this->purchase_cost,

            'status' => $this->status,
            'location' => $this->location,

            'maintenance_amount' => $maintenanceTotal ,
            'expense_amount' => $expenseTotal ,

            'total_amount' => ($maintenanceTotal + $expenseTotal +  $this->purchase_cost) ?? 0 ,

            'warranty_expiry_date' =>
                $this->warranty_expiry_date?->format('Y-m-d'),

            'notes' => $this->notes,

            'category' => new AssetCategoryResource(
                $this->whenLoaded('category')
            ),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
