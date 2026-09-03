<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\Category;

class CategoryRepository
{
    public function getAll()
    {
        return Category::with('items')->get();
    }

    public function getDetails(Category $category): ?Category
    {
        return $category->load('items');
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
