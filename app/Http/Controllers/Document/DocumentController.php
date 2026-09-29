<?php

namespace App\Http\Controllers\Document;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\AddNewDocument;
use App\Http\Requests\Document\UpdateDocument;
use App\Http\Resources\Document\DocumentResource;
use App\Http\Resources\Document\DocumentVersionResource;
use App\Models\Document\Document;
use App\Models\Document\DocumentVersion;
use App\Services\Document\DocumentService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class DocumentController extends Controller
{
    public function __construct(
        protected DocumentService $service
    ) {
    }

    public function index()
    {
        return ResponseHelper::success(
                    DocumentResource::collection($this->service->getAll()),
                    [
                        'en' => trans('validation.get_category_list', [], 'en'),
                        'ar' => trans('validation.get_category_list', [], 'ar'),
                    ],
                    Response::HTTP_OK
                );
    }

    public function store(
        AddNewDocument $request
    ) {

        try {

            return ResponseHelper::success(
                    new DocumentResource($this->service->create(
                                    $request->validated(),
                                    $request
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

    public function show(Document $document)
    {
        return ResponseHelper::success(
                new DocumentResource($this->service->getDetails($document)),
                [
                    'en' => trans('validation.get_category_details', [], 'en'),
                    'ar' => trans('validation.get_category_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }

    public function update(
        UpdateDocument $request,
        Document $document
    ) {
        try {

                return ResponseHelper::success(
                    new DocumentResource(
                        $this->service->update(
                                $document,
                                $request->validated(),
                                $request
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

    public function destroy(Document $document)
    {
        return ResponseHelper::success(
                $this->service->delete($document),
                [
                    'en' => trans('validation.delete_category', [], 'en'),
                    'ar' => trans('validation.delete_category', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }

    public function getDocumentVersion(
    Document $document,
    DocumentVersion $documentVersion
    ) {
        $file = $this->service->getDocumentVersion(
            $document,
            $documentVersion
        );

        return response()->file(
            $file['path'],
            [
                'Content-Type' => $file['mime_type'],
                'Content-Disposition' =>
                    'inline; filename="' . $file['file_name'] . '"',
            ]
        );
    }

    // public function uploadVersion(
    //     DocumentVersionRequest $request,
    //     Document $document
    // ) {
    //     return ResponseHelper::success(
    //             new DocumentVersionResource( $this->service->uploadVersion(
    //                 $document,
    //                 $request->file('file'),
    //                 $request->validated()['description'] ?? null
    //             )),
    //             [
    //                 'en' => trans('validation.delete_category', [], 'en'),
    //                 'ar' => trans('validation.delete_category', [], 'ar'),
    //             ],
    //             Response::HTTP_CREATED
    //         );
    // }
}
