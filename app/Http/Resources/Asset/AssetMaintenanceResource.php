<?php

namespace App\Http\Resources\Asset;

use App\Http\Resources\Asset\AssetResource;
use App\Http\Resources\Inventory\SupplierResource;
use App\Models\Asset\AssetMaintenance;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetMaintenanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $total_maintenance_by_asset = (float)  $this->asset_id->sum('cost');

        return [
            'id' => $this->id,

            'asset_id' => $this->asset_id,

            'maintenance_date' =>
                $this->maintenance_date?->format('Y-m-d'),

            'maintenance_type' => $this->maintenance_type,

            'description' => $this->description,

            'supplier_id' => $this->supplier_id,

            'cost' => $this->cost,

            'next_maintenance_date' =>
                $this->next_maintenance_date?->format('Y-m-d'),

            'status' => $this->status,

            'notes' => $this->notes,

            'total_maintenance_by_asset' => $total_maintenance_by_asset,

            'asset' => new AssetResource(
                $this->whenLoaded('asset')
            ),

            'supplier' => new SupplierResource(
                $this->whenLoaded('supplier')
            ),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
