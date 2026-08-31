<?php

namespace App\Http\Controllers\Inventory;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\Category\AddNewCategory;
use App\Http\Requests\Inventory\Category\UpdateCategory;
use App\Http\Resources\Inventory\CategoryResource;
use App\Models\Inventory\Category;
use App\Services\Inventory\CategoryService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $service
    ) {}

    public function index()
    {
        return ResponseHelper::success(
                    CategoryResource::collection($this->service->getAll()),
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
                    new CategoryResource($this->service->create(
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

    public function show(Category $category)
    {
        return ResponseHelper::success(
                new CategoryResource($this->service->getDetails($category->id)),
                [
                    'en' => trans('validation.get_category_details', [], 'en'),
                    'ar' => trans('validation.get_category_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }

    public function update(
        UpdateCategory $request,
        Category $category
    ) {

        try {

                return ResponseHelper::success(
                    new CategoryResource(
                        $this->service->update(
                                $category,
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

    public function destroy(Category $category)
    {
        return ResponseHelper::success(
                $this->service->delete($category->id),
                [
                    'en' => trans('validation.delete_category', [], 'en'),
                    'ar' => trans('validation.delete_category', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }
}
