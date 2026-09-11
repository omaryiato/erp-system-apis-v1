<?php

namespace App\Http\Resources\Asset;

use App\Http\Resources\Asset\AssetResource;
use App\Http\Resources\Inventory\SupplierResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $total_expense_by_asset = (float)  $this->asset_id->sum('amount');
        
        return [
            'id' => $this->id,

            'asset_id' => $this->asset_id,

            'expense_date' =>
                $this->expense_date?->format('Y-m-d'),

            'expense_type' => $this->expense_type,

            'description' => $this->description,

            'amount' => $this->amount,

            'supplier_id' => $this->supplier_id,

            'payment_method' => $this->payment_method,

            'reference_number' => $this->reference_number,

            'notes' => $this->notes,

            'total_expense_by_asset' => $total_expense_by_asset,

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
