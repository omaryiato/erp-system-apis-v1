<?php

namespace App\Http\Controllers\Asset;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\Category\AddNewCategory;
use App\Http\Requests\Asset\Category\UpdateCategory;
use App\Http\Resources\Asset\AssetCategoryResource;
use App\Models\Asset\AssetCategory;
use App\Services\Asset\AssetCategoryService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AssetCategoryController extends Controller
{
    protected AssetCategoryService $service;

    public function __construct(
        AssetCategoryService $service
    ) {
        $this->service = $service;
    }

    public function index()
    {

        return ResponseHelper::success(
                    AssetCategoryResource::collection($this->service->getAll()),
                    [
                        'en' => trans('validation.get_category_list', [], 'en'),
                        'ar' => trans('validation.get_category_list', [], 'ar'),
                    ],
                    Response::HTTP_OK
                );
    }

    public function show(AssetCategory $assetCategory)
    {
        return ResponseHelper::success(
                new AssetCategoryResource($this->service->getDetails($assetCategory)),
                [
                    'en' => trans('validation.get_category_details', [], 'en'),
                    'ar' => trans('validation.get_category_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }

    public function store(
        AddNewCategory $request
    ) {

        try {
            return ResponseHelper::success(
                    new AssetCategoryResource( $this->service->create(
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

    public function update(
        UpdateCategory $request,
        AssetCategory $assetCategory
    ) {

        try {

                return ResponseHelper::success(
                    new AssetCategoryResource(
                        $this->service->update(
                        $assetCategory,
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

    public function destroy(AssetCategory $assetCategory)
    {
        return ResponseHelper::success(
                $this->service->delete($assetCategory),
                [
                    'en' => trans('validation.delete_category', [], 'en'),
                    'ar' => trans('validation.delete_category', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }
}
