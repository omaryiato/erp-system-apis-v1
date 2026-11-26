<?php

namespace App\Http\Controllers\Inventory;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\CashTransaction\AddCashTransaction;
use App\Http\Resources\Inventory\CashTransactionResource;
use App\Services\Inventory\CashTransactionService;
use App\Models\Inventory\CashTransaction;
use Symfony\Component\HttpFoundation\Response;

class CashTransactionController extends Controller
{
    public function __construct(
        private CashTransactionService $service
    ) {}

    public function index() {
            return ResponseHelper::success(
                CashTransactionResource::collection(
                    $this->service->getAll()
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
                Response::HTTP_OK
            );
    }

    public function store(AddCashTransaction $request)
    {
        $transaction = $this->service->create($request->validated(), $request->user()->id);

        return ResponseHelper::success(
            new CashTransactionResource($transaction),
            ['en' => 'Cash transaction created.', 'ar' => 'تم إنشاء الحركة النقدية.'],
            Response::HTTP_CREATED
        );
    }

    public function update(AddCashTransaction $request, CashTransaction $cashTransaction)
    {
        $transaction = $this->service->update($cashTransaction, $request->validated(), $request->user()->id);

        return ResponseHelper::success(
            new CashTransactionResource($transaction),
            ['en' => 'Cash transaction updated.', 'ar' => 'تم تحديث الحركة النقدية.'],
            Response::HTTP_OK
        );
    }
}
