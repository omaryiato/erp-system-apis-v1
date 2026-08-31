<?php

namespace App\Http\Controllers\Inventory;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\Supplier\AddNewSupplier;
use App\Http\Requests\Inventory\Supplier\UpdateSupplier;
use App\Http\Resources\Inventory\SupplierResource;
use App\Models\Inventory\Supplier;
use App\Services\Inventory\SupplierService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class SupplierController extends Controller
{
    public function __construct(
        private SupplierService $service
    ) {}

    public function index()
    {
        return ResponseHelper::success(
                    SupplierResource::collection($this->service->getAll()),
                    [
                        'en' => trans('validation.get_supplier_list', [], 'en'),
                        'ar' => trans('validation.get_supplier_list', [], 'ar'),
                    ],
                    Response::HTTP_OK
                );
    }

    public function store(
        AddNewSupplier $request
    ) {

        try {

            return ResponseHelper::success(
                    new SupplierResource($this->service->create(
                        $request->validated()
                    )),
                    [
                        'en' => trans('validation.add_new_supplier', [], 'en'),
                        'ar' => trans('validation.add_new_supplier', [], 'ar'),
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

    public function show(Supplier $supplier)
    {
        return ResponseHelper::success(
                new SupplierResource($this->service->getDetails($supplier->id)),
                [
                    'en' => trans('validation.get_supplier_details', [], 'en'),
                    'ar' => trans('validation.get_supplier_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );

    }

    public function update(
        UpdateSupplier $request,
        Supplier $supplier
    ) {

        try {

                return ResponseHelper::success(
                    new SupplierResource(
                        $this->service->update(
                            $supplier->id,
                            $request->validated()
                        )),
                    [
                        'en' => trans('validation.update_supplier', [], 'en'),
                        'ar' => trans('validation.update_supplier', [], 'ar'),
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

    public function destroy(Supplier $supplier)
    {

        return ResponseHelper::success(
                $this->service->delete($supplier->id),
                [
                    'en' => trans('validation.delete_supplier', [], 'en'),
                    'ar' => trans('validation.delete_supplier', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }
}
