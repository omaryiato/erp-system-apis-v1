<?php

namespace App\Http\Repositories\Asset;

use App\Models\Asset\AssetMaintenance;
use Illuminate\Database\Eloquent\Collection;

class AssetMaintenanceRepository
{


    public function getAll()
    {
        return AssetMaintenance::with([
                'asset',
                'supplier',
            ])
            ->orderBy('maintenance_date', 'desc')
            ->get();
    }

    public function getDetails(AssetMaintenance $assetMaintenance): AssetMaintenance
    {
        return $assetMaintenance->load([
                    'asset',
                    'supplier',
                ]);
    }

    public function create(array $maintenance_request): AssetMaintenance
    {
        return AssetMaintenance::create($maintenance_request);
    }

    public function update(
        AssetMaintenance $assetMaintenance,
        array $maintenance_request
    ): AssetMaintenance {

        $assetMaintenance->update($maintenance_request);

        return $assetMaintenance->refresh();
    }

    public function delete(AssetMaintenance $assetMaintenance): bool
    {
        return $assetMaintenance->delete();
    }

}
