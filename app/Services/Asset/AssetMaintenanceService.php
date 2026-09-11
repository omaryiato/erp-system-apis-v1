<?php

namespace App\Services\Asset;

use App\Http\Repositories\Asset\AssetMaintenanceRepository;
use App\Http\Repositories\Asset\AssetRepository;
use App\Models\Asset\AssetMaintenance;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AssetMaintenanceService
{
    protected AssetMaintenanceRepository $repository;
    protected AssetRepository $assetRepository;

    public function __construct(
        AssetMaintenanceRepository $repository,
        AssetRepository $assetRepository
    ) {
        $this->repository = $repository;
        $this->assetRepository = $assetRepository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getDetails(AssetMaintenance $assetMaintenance): AssetMaintenance
    {
        return $this->repository->getDetails($assetMaintenance);
    }


    public function create(array $maintenance_request): AssetMaintenance
    {
        return DB::transaction(function () use ($maintenance_request) {

            $maintenance = $this->repository->create(
                $maintenance_request
            );

            /*
             * Automatically mark the asset
             * as being under maintenance.
             */
            $this->assetRepository->update(
                $maintenance_request['asset_id'],
                [
                    'status' => 'IN_MAINTENANCE',
                ]
            );

            return $maintenance->load([
                'asset',
                'supplier',
            ]);
        });
    }

    public function update(
        AssetMaintenance $assetMaintenance,
        array $maintenance_request
    ): AssetMaintenance {
        return DB::transaction(function () use ($assetMaintenance, $maintenance_request) {

            return $this->repository->update(
                $assetMaintenance,
                $maintenance_request
            );
        });
    }

    public function delete(AssetMaintenance $assetMaintenance): bool
    {
        return DB::transaction(function () use ($assetMaintenance) {

            return $this->repository->delete($assetMaintenance);
        });
    }

    public function prepareMaintenanceInfo(array $maintenance_request)
    {
        $maintenance_data =  [
            'asset_id' => $maintenance_request['asset_id'] ?? null,
            'maintenance_date' => $maintenance_request['maintenance_date'] ?? null,
            'maintenance_type' => $maintenance_request['maintenance_type'] ?? null,
            'description' => $maintenance_request['description'] ?? null,
            'supplier_id' => $maintenance_request['supplier_id'] ?? null,
            'cost' => $maintenance_request['cost'] ?? null,
            'next_maintenance_date' => $maintenance_request['next_maintenance_date'] ?? null,
            'status' => $maintenance_request['status'] ?? 'IN_PROGRESS',
            'notes' => $maintenance_request['notes'] ?? null,
        ];

        return $maintenance_data;
    }

}
