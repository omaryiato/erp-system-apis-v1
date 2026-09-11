<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\Expense\AddNewExpense;
use App\Http\Requests\Asset\Expense\UpdateExpense;
use App\Services\Asset\AssetExpenseService;
use Illuminate\Http\JsonResponse;
use App\Models\Asset\AssetExpense;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\ResponseHelper;
use App\Http\Resources\Asset\AssetExpenseResource;

class AssetExpenseController extends Controller
{
    protected AssetExpenseService $service;

    public function __construct(
        AssetExpenseService $service
    ) {
        $this->service = $service;
    }

    public function index()
    {
        return ResponseHelper::success(
                    AssetExpenseResource::collection($this->service->getAll()),
                    [
                        'en' => trans('validation.get_category_list', [], 'en'),
                        'ar' => trans('validation.get_category_list', [], 'ar'),
                    ],
                    Response::HTTP_OK
                );
    }

    public function show(AssetExpense $assetExpense)
    {
        return ResponseHelper::success(
                new AssetExpenseResource($this->service->getDetails($assetExpense)),
                [
                    'en' => trans('validation.get_category_details', [], 'en'),
                    'ar' => trans('validation.get_category_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }




    public function store(
        AddNewExpense $request
    ) {
        try {
            return ResponseHelper::success(
                    new AssetExpenseResource( $this->service->create(
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
        UpdateExpense $request,
        AssetExpense $assetExpense
    ) {

        try {

            return ResponseHelper::success(
                new AssetExpenseResource(
                    $this->service->update(
                    $assetExpense,
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

    public function destroy(AssetExpense $assetExpense)
    {
        return ResponseHelper::success(
                $this->service->delete($assetExpense),
                [
                    'en' => trans('validation.delete_category', [], 'en'),
                    'ar' => trans('validation.delete_category', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }

}
