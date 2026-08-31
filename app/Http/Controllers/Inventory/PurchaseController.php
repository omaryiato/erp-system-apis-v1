<?php

namespace App\Http\Controllers\Inventory;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\Purchase\AddNewPurchase;
use App\Http\Resources\Inventory\PurchaseResource;
use App\Models\Inventory\Purchase;
use App\Services\Inventory\PurchaseService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class PurchaseController extends Controller
{
    public function __construct(
        private PurchaseService $service
    ) {}

    public function index()
    {
        return ResponseHelper::success(
                    PurchaseResource::collection($this->service->getAll()),
                    [
                        'en' => trans('validation.get_purchase_list', [], 'en'),
                        'ar' => trans('validation.get_purchase_list', [], 'ar'),
                    ],
                    Response::HTTP_OK
                );
    }

    public function store(
        AddNewPurchase $request
    ) {

        try {

            return ResponseHelper::success(
                    new PurchaseResource($this->service->create(
                        $request->validated()
                    )),
                    [
                        'en' => trans('validation.add_new_purchase', [], 'en'),
                        'ar' => trans('validation.add_new_purchase', [], 'ar'),
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

    public function show(Purchase $purchase)
    {
        return ResponseHelper::success(
                new PurchaseResource($this->service->getDetails($purchase->id)),
                [
                    'en' => trans('validation.get_purchase_details', [], 'en'),
                    'ar' => trans('validation.get_purchase_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }
}
