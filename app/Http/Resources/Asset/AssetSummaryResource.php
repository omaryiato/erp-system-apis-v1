<?php

namespace App\Http\Resources\Asset;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Assets
            |--------------------------------------------------------------------------
            */

            'total_assets' => $this->total_assets,

            'active_assets' => $this->active_assets,

            'maintenance_assets' => $this->maintenance_assets,

            'damaged_assets' => $this->damaged_assets,

            'disposed_assets' => $this->disposed_assets,

            'lost_assets' => $this->lost_assets,


            /*
            |--------------------------------------------------------------------------
            | Asset Values
            |--------------------------------------------------------------------------
            */

            'total_purchase_cost' => $this->total_purchase_cost,

            'total_current_value' => $this->total_current_value,


            /*
            |--------------------------------------------------------------------------
            | Maintenance
            |--------------------------------------------------------------------------
            */

            'total_maintenance_count' => $this->total_maintenance_count,

            'total_maintenance_cost' => $this->total_maintenance_cost,


            /*
            |--------------------------------------------------------------------------
            | Expenses
            |--------------------------------------------------------------------------
            */

            'total_expense_count' => $this->total_expense_count,

            'total_expenses' => $this->total_expenses,


            /*
            |--------------------------------------------------------------------------
            | Total Cost
            |--------------------------------------------------------------------------
            */

            'total_asset_cost' => $this->total_asset_cost,
        ];
    }
}
