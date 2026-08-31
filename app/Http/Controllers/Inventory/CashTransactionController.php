<?php

namespace App\Http\Controllers\Inventory;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\CashTransaction\AddCashTransaction;
use App\Http\Requests\Inventory\CashTransaction\AddExpensePayment;
use App\Http\Requests\Inventory\CashTransaction\AddRevenuePayment;
use App\Http\Resources\Inventory\CashTransactionResource;
use App\Services\Inventory\CashTransactionService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class CashTransactionController extends Controller
{
    public function __construct(
        private CashTransactionService $service
    ) {}

    public function store(
    AddCashTransaction $request
    ) {
        try {

            return ResponseHelper::success(
                new CashTransactionResource(
                    $this->service->create(
                        $request->validated()
                    )
                ),
                [
                    'en' => trans(
                        'validation.add_new_cash_transaction',
                        [],
                        'en'
                    ),
                    'ar' => trans(
                        'validation.add_new_cash_transaction',
                        [],
                        'ar'
                    ),
                ],
                Response::HTTP_CREATED
            );

        } catch (Exception $exception) {

            return ResponseHelper::error(
                [
                    'en' => trans(
                        'validation.exception_error',
                        [],
                        'en'
                    ),
                    'ar' => trans(
                        'validation.exception_error',
                        [],
                        'ar'
                    ),
                ],
                $exception->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
