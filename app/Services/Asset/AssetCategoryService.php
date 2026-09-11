<?php

namespace App\Services\Asset;

use App\Http\Repositories\Asset\AssetCategoryRepository;
use App\Models\Asset\AssetCategory;
use Illuminate\Database\Eloquent\Collection;

class AssetCategoryService
{
    protected AssetCategoryRepository $repository;

    public function __construct(
        AssetCategoryRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getDetails(AssetCategory $assetCategory): AssetCategory
    {
        return $this->repository->getDetails($assetCategory);
    }

    public function create(array $category_request): AssetCategory
    {
        return $this->repository->create($this->prepareCategoryInfo($category_request));
    }

    public function update(
        AssetCategory $assetCategory,
        array $category_request
    ): AssetCategory {
        return $this->repository->update($assetCategory, $this->prepareCategoryInfo($category_request));
    }

    public function delete(AssetCategory $assetCategory): bool
    {

        /*
         * Prevent deleting a category that
         * still contains assets.
         */
        if ($assetCategory->assets()->exists()) {
            throw new \Exception(
                'Cannot delete category because it contains assets.'
            );
        }

        return $this->repository->delete($assetCategory);
    }

    public function prepareCategoryInfo(array $category_request)
    {
        $category_data =  [
            'category_code' => $category_request['category_code'] ?? null,
            'category_name' => $category_request['category_name'] ?? null,
            'description' => $category_request['description'] ?? null,
            'is_active' => $category_request['is_active'] ?? 1,
        ];

        return $category_data;
    }
}
