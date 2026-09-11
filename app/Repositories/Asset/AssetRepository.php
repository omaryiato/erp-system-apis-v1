<?php

namespace App\Http\Repositories\Asset;

use App\Models\Asset\Asset;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AssetRepository
{


    public function getAll()
    {

        return Asset::with([
                    'category',
                    'maintenances',
                    'expenses',
                    'maintenances.supplier',
                    'expenses.supplier',
                ])
                ->orderBy('id', 'desc')
                ->get();
    }


    public function getDetails(Asset $asset): Asset
    {
        return $asset->load([
                    'category',
                    'maintenances',
                    'expenses',
                    'maintenances.supplier',
                    'expenses.supplier',
                ]);
    }


    public function create(array $asset_request): Asset
    {
        return Asset::create($asset_request);
    }

    public function update(Asset $asset, array $asset_request): Asset
    {
        $asset->update($asset_request);
        return $asset->refresh();
    }

    public function delete(Asset $asset): bool
    {
        return $asset->delete();
    }


public function assetsReport(): array
{
    $summary = Asset::selectRaw('
            COUNT(*) as total_assets,

            COALESCE(SUM(purchase_cost), 0) as total_purchase_cost,

            COALESCE(SUM(current_value), 0) as total_current_value,

            COUNT(*) FILTER (
                WHERE status = ?
            ) as active_assets,

            COUNT(*) FILTER (
                WHERE status = ?
            ) as maintenance_assets,

            COUNT(*) FILTER (
                WHERE status = ?
            ) as damaged_assets,

            COUNT(*) FILTER (
                WHERE status = ?
            ) as disposed_assets,

            COUNT(*) FILTER (
                WHERE status = ?
            ) as lost_assets
        ', [
            'ACTIVE',
            'IN_MAINTENANCE',
            'DAMAGED',
            'DISPOSED',
            'LOST',
        ])
        ->first();

    $maintenance = DB::table('asset_maintenance_v1')
        ->selectRaw('
            COUNT(*) as total_maintenance_count,
            COALESCE(SUM(cost), 0) as total_maintenance_cost
        ')
        ->first();

    $expenses = DB::table('asset_expenses_v1')
        ->selectRaw('
            COUNT(*) as total_expense_count,
            COALESCE(SUM(amount), 0) as total_expenses
        ')
        ->first();

    return [
        'total_assets' => (int) $summary->total_assets,

        'total_purchase_cost' => (float) $summary->total_purchase_cost,

        'total_current_value' => (float) $summary->total_current_value,

        'total_maintenance_count' => (int) $maintenance->total_maintenance_count,

        'total_maintenance_cost' => (float) $maintenance->total_maintenance_cost,

        'total_expense_count' => (int) $expenses->total_expense_count,

        'total_expenses' => (float) $expenses->total_expenses,

        'total_asset_cost' =>
            (float) $summary->total_purchase_cost
            + (float) $maintenance->total_maintenance_cost
            + (float) $expenses->total_expenses,

        'active_assets' => (int) $summary->active_assets,

        'maintenance_assets' => (int) $summary->maintenance_assets,

        'damaged_assets' => (int) $summary->damaged_assets,

        'disposed_assets' => (int) $summary->disposed_assets,

        'lost_assets' => (int) $summary->lost_assets,
    ];
}


}
