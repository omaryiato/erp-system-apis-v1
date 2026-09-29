<?php

namespace App\Http\Controllers\Document;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\AddNewDocumentCategory;
use App\Http\Requests\Document\UpdateDocumentCategory;
use App\Http\Resources\Document\DocumentCategoryResource;
use App\Models\Document\DocumentCategory;
use App\Services\Document\DocumentCategoryService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class DocumentCategoryController extends Controller
{
    public function __construct(
        protected DocumentCategoryService $service
    ) {
    }

    public function index()
    {
        return ResponseHelper::success(
                    DocumentCategoryResource::collection($this->service->getAll()),
                    [
                        'en' => trans('validation.get_category_list', [], 'en'),
                        'ar' => trans('validation.get_category_list', [], 'ar'),
                    ],
                    Response::HTTP_OK
                );
    }

    public function store(
        AddNewDocumentCategory $request
    ) {
        try {

            return ResponseHelper::success(
                    new DocumentCategoryResource($this->service->create(
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

    public function show(DocumentCategory $documentCategory)
    {
        return ResponseHelper::success(
                new DocumentCategoryResource($this->service->getDetails($documentCategory)),
                [
                    'en' => trans('validation.get_category_details', [], 'en'),
                    'ar' => trans('validation.get_category_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }

    public function update(
        UpdateDocumentCategory $request,
        DocumentCategory $documentCategory
    ) {

        try {

                return ResponseHelper::success(
                    new DocumentCategoryResource(
                        $this->service->update(
                                $documentCategory,
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

    public function destroy(DocumentCategory $documentCategory)
    {
        return ResponseHelper::success(
                $this->service->delete($documentCategory),
                [
                    'en' => trans('validation.delete_category', [], 'en'),
                    'ar' => trans('validation.delete_category', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }
}
