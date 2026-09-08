<?php

namespace App\Http\Controllers\Inventory;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\Category\AddNewCategory;
use App\Http\Requests\Inventory\Category\UpdateCategory;
use App\Http\Resources\Inventory\ExpensesCategoryResource;
use App\Models\Inventory\ExpensesCategory;
use App\Services\Inventory\ExpensesCategoryService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ExpensesCategoryController extends Controller
{
    public function __construct(
        private ExpensesCategoryService $service
    ) {}

    public function index()
    {
        return ResponseHelper::success(
                    ExpensesCategoryResource::collection($this->service->getAll()),
                    [
                        'en' => trans('validation.get_category_list', [], 'en'),
                        'ar' => trans('validation.get_category_list', [], 'ar'),
                    ],
                    Response::HTTP_OK
                );
    }

    public function store(AddNewCategory $request)
    {

        try {

            return ResponseHelper::success(
                    new ExpensesCategoryResource($this->service->create(
                                    $request->validated()
                                )),
                    [
                        'en' => trans('validation.add_new_category', [], 'en'),
                        'ar' => trans('validation.add_new_category', [], 'ar'),
                    ],
                    Response::HTTP_CREATED
                );
        } catch (Exception $exception) {
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }
    }

    public function show(ExpensesCategory $expensesCategory)
    {
        return ResponseHelper::success(
                new ExpensesCategoryResource($this->service->getDetails($expensesCategory)),
                [
                    'en' => trans('validation.get_category_details', [], 'en'),
                    'ar' => trans('validation.get_category_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }

    public function update(
        UpdateCategory $request,
        ExpensesCategory $expensesCategory
    ) {

        try {

                return ResponseHelper::success(
                    new ExpensesCategoryResource(
                        $this->service->update(
                                $expensesCategory,
                                $request->validated()
                            )),
                    [
                        'en' => trans('validation.update_category', [], 'en'),
                        'ar' => trans('validation.update_category', [], 'ar'),
                    ],
                    Response::HTTP_CREATED
                );
        } catch (Exception $exception) {
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }
    }

    public function destroy(ExpensesCategory $expensesCategory)
    {
        return ResponseHelper::success(
                $this->service->delete($expensesCategory),
                [
                    'en' => trans('validation.delete_category', [], 'en'),
                    'ar' => trans('validation.delete_category', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }
}
