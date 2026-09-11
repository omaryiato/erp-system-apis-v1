<?php

namespace App\Http\Repositories\Asset;

use App\Models\Asset\AssetCategory;
use Illuminate\Database\Eloquent\Collection;

class AssetCategoryRepository
{

    public function getAll(): Collection
    {
        return AssetCategory::withCount('assets')
            ->orderBy('id', 'desc')
            ->get();
    }


    public function getDetails(AssetCategory $assetCategory): AssetCategory
    {
        return $assetCategory;
    }

    public function create(array $category_request): AssetCategory
    {
        return AssetCategory::create($category_request);
    }

    public function update(AssetCategory $assetCategory, array $category_request): AssetCategory
    {
        $assetCategory->update($category_request);

        return $assetCategory->refresh();
    }

    public function delete(AssetCategory $assetCategory): bool
    {
        return $assetCategory->delete();
    }
}
