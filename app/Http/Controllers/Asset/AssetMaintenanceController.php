<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\Maintenance\AddNewMaintenance;
use App\Http\Requests\Asset\Maintenance\UpdateMaintenance;
use App\Services\Asset\AssetMaintenanceService;
use App\Helpers\ResponseHelper;
use App\Http\Resources\Asset\AssetMaintenanceResource;
use App\Models\Asset\AssetMaintenance;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class AssetMaintenanceController extends Controller
{
    protected AssetMaintenanceService $service;

    public function __construct(
        AssetMaintenanceService $service
    ) {
        $this->service = $service;
    }

    public function index()
    {
        return ResponseHelper::success(
                    AssetMaintenanceResource::collection($this->service->getAll()),
                    [
                        'en' => trans('validation.get_category_list', [], 'en'),
                        'ar' => trans('validation.get_category_list', [], 'ar'),
                    ],
                    Response::HTTP_OK
                );
    }

    public function show(AssetMaintenance $assetMaintenance)
    {
        return ResponseHelper::success(
                new AssetMaintenanceResource($this->service->getDetails($assetMaintenance)),
                [
                    'en' => trans('validation.get_category_details', [], 'en'),
                    'ar' => trans('validation.get_category_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }

    public function store(
        AddNewMaintenance $request
    ) {

        try {
            return ResponseHelper::success(
                    new AssetMaintenanceResource( $this->service->create(
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
        UpdateMaintenance $request,
        AssetMaintenance $assetMaintenance
    ) {


        try {

            return ResponseHelper::success(
                new AssetMaintenanceResource(
                    $this->service->update(
                    $assetMaintenance,
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


    public function destroy(AssetMaintenance $assetMaintenance)
    {
        return ResponseHelper::success(
                $this->service->delete($assetMaintenance),
                [
                    'en' => trans('validation.delete_category', [], 'en'),
                    'ar' => trans('validation.delete_category', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }

}
