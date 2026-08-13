<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Requests\HR\EmployeePayrollTransaction\AddNewEmployeePayrollTransaction;
use App\Http\Resources\EmployeePayrollTransactionResource;
use App\Models\HR\EmployeePayrollTransaction;
use App\Services\HR\EmployeePayrollTransactionService;

class EmployeePayrollTransactionController extends Controller
{
    public function __construct(
        protected EmployeePayrollTransactionService $service
    ) {}

    public function index()
    {
        return EmployeePayrollTransactionResource::collection(
            $this->service->getAll()
        );
    }

    public function store(
        AddNewEmployeePayrollTransaction $request
    ) {
        $transaction = $this->service->create(
            $request->validated()
        );

        return new EmployeePayrollTransactionResource(
            $transaction
        );
    }

    public function show(
        EmployeePayrollTransaction $transaction
    ) {
        return new EmployeePayrollTransactionResource(
            $this->service->find($transaction->id)
        );
    }

    public function cancel(
        EmployeePayrollTransaction $transaction
    ) {
        $transaction = $this->service->cancel(
            $transaction
        );

        return new EmployeePayrollTransactionResource(
            $transaction
        );
    }
}
