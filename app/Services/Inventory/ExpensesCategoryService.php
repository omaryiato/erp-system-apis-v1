<?php

namespace App\Services\Inventory;

use App\Models\Inventory\ExpensesCategory;
use App\Repositories\Inventory\ExpensesCategoryRepository;

class ExpensesCategoryService
{
    public function __construct(
        private ExpensesCategoryRepository $repository
    ) {}

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getDetails(ExpensesCategory $expensesCategory): ?ExpensesCategory
    {
        return $this->repository->getDetails($expensesCategory);
    }

    public function create(array $category_request): ExpensesCategory
    {
        return $this->repository->create($this->prepareCategoryInfo($category_request));
    }

    public function update(
        ExpensesCategory $expensesCategory,
        array $category_request
    ): ExpensesCategory {
        return $this->repository->update(
            $expensesCategory,
            $this->prepareCategoryInfo($category_request)
        );
    }

    public function delete(ExpensesCategory $expensesCategory): bool
    {
        return $this->repository->delete($expensesCategory);
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
