<?php

namespace App\Services\Asset;

use App\Http\Repositories\Asset\AssetRepository;
use App\Models\Asset\Asset;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AssetService
{
    protected AssetRepository $repository;

    public function __construct(
        AssetRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }


    public function getDetails(Asset $asset): Asset
    {
        return $this->repository->getDetails($asset);
    }


    public function create(array $asset_request): Asset
    {
        return DB::transaction(function () use ($asset_request) {

            /*
             * When creating an asset,
             * current value starts with purchase cost
             * unless explicitly provided.
             */
            if (!isset($asset_request['current_value'])) {
                $asset_request['current_value'] =
                    $asset_request['purchase_cost'] ?? 0;
            }

            return $this->repository->create($this->prepareAssetInfo($asset_request));
        });
    }

    public function update(
        Asset $asset,
        array $asset_request
    ): Asset {
        return DB::transaction(function () use ($asset, $asset_request) {

            return $this->repository->update(
                $asset,
                $this->prepareAssetInfo($asset_request)
            );
        });
    }

    public function delete(Asset $asset): bool
    {
        return DB::transaction(function () use ($asset) {


            /*
             * Usually an asset should not be deleted
             * if it has financial/history records.
             */
            if (
                $asset->maintenances()->exists() ||
                $asset->expenses()->exists()
            ) {
                throw new \Exception(
                    'Cannot delete asset because it has maintenance or expense records.'
                );
            }

            return $this->repository->delete($asset);
        });
    }

    public function prepareAssetInfo(array $asset_request)
    {

        $asset_data =  [
            'asset_code' => $asset_request['asset_code'] ?? null,
            'asset_name' => $asset_request['asset_name'] ?? null,
            'category_id' => $asset_request['category_id'] ?? null,
            'description' => $asset_request['description'] ?? null,
            'serial_number' => $asset_request['serial_number'] ?? null,
            'model' => $asset_request['model'] ?? null,
            'manufacturer' => $asset_request['manufacturer'] ?? null,
            'purchase_date' => $asset_request['purchase_date'] ?? null,
            'purchase_cost' => $asset_request['purchase_cost'] ?? null,
            'status' => $asset_request['status'] ?? 'ACTIVE',
            'location' => $asset_request['location'] ?? null,
            'warranty_expiry_date' => $asset_request['warranty_expiry_date'] ?? null,
            'notes' => $asset_request['notes'] ?? null,
        ];

        return $asset_data;
    }


    public function assetsReport(): array
    {
        return $this->repository->assetsReport();
    }


}
