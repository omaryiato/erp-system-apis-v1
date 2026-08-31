<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\Category;

class CategoryRepository
{
    public function getAll()
    {
        return Category::query()
            ->withCount('items')
            ->latest('id');
    }

    public function getDetails(int $id): ?Category
    {
        return Category::query()
            ->withCount('items')
            ->find($id);
    }

    public function create(array $category_request): Category
    {
        return Category::create($category_request);
    }

    public function update(
        Category $category,
        array $category_request
    ): Category {
        $category->update($category_request);

        return $category->refresh();
    }

    public function delete(Category $category): bool
    {
        return (bool) $category->delete();
    }
}
