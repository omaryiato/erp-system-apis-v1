<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Category;
use App\Repositories\Inventory\CategoryRepository;

class CategoryService
{
    public function __construct(
        private CategoryRepository $repository
    ) {}

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getDetails(Category $category): ?Category
    {
        return $this->repository->getDetails($category);
    }

    public function create(array $category_request): Category
    {
        return $this->repository->create($this->prepareCategoryInfo($category_request));
    }

    public function update(
        Category $category,
        array $category_request
    ): Category {
        return $this->repository->update(
            $category,
            $this->prepareCategoryInfo($category_request)
        );
    }

    public function delete(Category $category): bool
    {
        return $this->repository->delete($category);
    }

    public function prepareCategoryInfo(array $category_request)
    {
        $category_data =  [
            'name' => $category_request['name'] ?? null,
            'description' => $category_request['description'] ?? null,
            'status' => $category_request['status'] ?? null,
        ];

        return $category_data;
    }
}
