<?php

namespace App\Http\Controllers\Asset;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\Asset\AddNewAsset;
use App\Http\Requests\Asset\Asset\UpdateAsset;
use App\Http\Resources\Asset\AssetResource;
use App\Http\Resources\Asset\AssetSummaryResource;
use App\Models\Asset\Asset;
use App\Services\Asset\AssetService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class AssetController extends Controller
{
    protected AssetService $service;

    public function __construct(
        AssetService $service
    ) {
        $this->service = $service;
    }

    public function index()
    {
        return ResponseHelper::success(
                    AssetResource::collection($this->service->getAll()),
                    [
                        'en' => trans('validation.get_category_list', [], 'en'),
                        'ar' => trans('validation.get_category_list', [], 'ar'),
                    ],
                    Response::HTTP_OK
                );
    }

    public function show(Asset $asset)
    {
        return ResponseHelper::success(
                new AssetResource($this->service->getDetails($asset)),
                [
                    'en' => trans('validation.get_category_details', [], 'en'),
                    'ar' => trans('validation.get_category_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }


    public function store(
        AddNewAsset $request
    ) {

        try {
            return ResponseHelper::success(
                    new AssetResource( $this->service->create(
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
        UpdateAsset $request,
        Asset $asset
    ) {

        try {

            return ResponseHelper::success(
                new AssetResource(
                    $this->service->update(
                    $asset,
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

    public function destroy(Asset $asset)
    {
        return ResponseHelper::success(
                $this->service->delete($asset),
                [
                    'en' => trans('validation.delete_category', [], 'en'),
                    'ar' => trans('validation.delete_category', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }

    public function assetsReport()
    {
        return ResponseHelper::success(
                new AssetSummaryResource($this->service->assetsReport()),
                [
                    'en' => trans('validation.delete_category', [], 'en'),
                    'ar' => trans('validation.delete_category', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );

    }



}
