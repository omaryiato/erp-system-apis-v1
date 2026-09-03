<?php

namespace App\Http\Controllers\Inventory;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\Expense\AddNewExpense;
use App\Http\Resources\Inventory\ExpenseResource;
use App\Models\Inventory\Expense;
use App\Services\Inventory\ExpenseService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ExpenseController extends Controller
{
    public function __construct(
        private ExpenseService $service
    ) {}

    public function index()
    {
        return ResponseHelper::success(
                    ExpenseResource::collection($this->service->getAll()),
                    [
                        'en' => trans('validation.get_expenses_list', [], 'en'),
                        'ar' => trans('validation.get_expenses_list', [], 'ar'),
                    ],
                    Response::HTTP_OK
                );
    }

    public function store(
        AddNewExpense $request
    ) {

        try {

            return ResponseHelper::success(
                    new ExpenseResource($this->service->create(
                            $request->validated()
                        )),
                    [
                        'en' => trans('validation.add_new_expense', [], 'en'),
                        'ar' => trans('validation.add_new_expense', [], 'ar'),
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

    public function show(Expense $expense)
    {

        return ResponseHelper::success(
                new ExpenseResource($this->service->getDetails($expense)),
                [
                    'en' => trans('validation.get_expense_details', [], 'en'),
                    'ar' => trans('validation.get_expense_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }
}
