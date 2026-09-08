<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\ExpensesCategory;

class ExpensesCategoryRepository
{
    public function getAll()
    {
        return ExpensesCategory::all();
    }

    public function getDetails(ExpensesCategory $expensesCategory): ?ExpensesCategory
    {
        return $expensesCategory;
    }

    public function create(array $category_request): ExpensesCategory
    {
        return ExpensesCategory::create($category_request);
    }

    public function update(
        ExpensesCategory $expensesCategory,
        array $category_request
    ): ExpensesCategory {
        $expensesCategory->update($category_request);

        return $expensesCategory->refresh();
    }

    public function delete(ExpensesCategory $expensesCategory): bool
    {
        return (bool) $expensesCategory->delete();
    }
}
