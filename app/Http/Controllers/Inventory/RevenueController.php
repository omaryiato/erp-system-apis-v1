<?php

namespace App\Http\Controllers\Inventory;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\Revenue\AddNewRevenue;
use App\Http\Resources\Inventory\RevenueResource;
use App\Models\Inventory\Revenue;
use App\Services\Inventory\RevenueService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RevenueController extends Controller
{
    public function __construct(
        private RevenueService $service
    ) {}

    public function index()
    {
        return ResponseHelper::success(
                    RevenueResource::collection($this->service->getAll()),
                    [
                        'en' => trans('validation.get_revenue_list', [], 'en'),
                        'ar' => trans('validation.get_revenue_list', [], 'ar'),
                    ],
                    Response::HTTP_OK
                );
    }

    public function store(
        AddNewRevenue $request
    ) {

        try {

            return ResponseHelper::success(
                    new RevenueResource($this->service->create(
                            $request->validated()
                        )),
                    [
                        'en' => trans('validation.add_new_revenue', [], 'en'),
                        'ar' => trans('validation.add_new_revenue', [], 'ar'),
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

    public function show(Revenue $revenue)
    {

        return ResponseHelper::success(
                new RevenueResource($this->service->getDetails($revenue->id)),
                [
                    'en' => trans('validation.get_revenue_details', [], 'en'),
                    'ar' => trans('validation.get_revenue_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }
}
