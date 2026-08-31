<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\Reports\ReportFilterRequest;
use App\Http\Resources\Inventory\CashTransactionResource;
use App\Http\Resources\Inventory\Reports\CashFlowResource;
use App\Http\Resources\Inventory\Reports\CashTransactionReportResource;
use App\Http\Resources\Inventory\Reports\ExpenseReportResource;
use App\Http\Resources\Inventory\Reports\FinancialSummaryResource;
use App\Http\Resources\Inventory\Reports\MonthlyFinancialResource;
use App\Http\Resources\Inventory\Reports\OutstandingExpenseResource;
use App\Http\Resources\Inventory\Reports\OutstandingRevenueResource;
use App\Http\Resources\Inventory\Reports\ProjectFinancialResource;
use App\Http\Resources\Inventory\Reports\RevenueReportResource;
use App\Http\Resources\Inventory\Reports\SupplierFinancialResource;
use App\Services\Inventory\ReportService;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function __construct(
        private ReportService $service
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Financial Summary
    |--------------------------------------------------------------------------
    */

    public function financialSummary(
        ReportFilterRequest $request
    ) {
        return new FinancialSummaryResource(
            $this->service->financialSummary(
                $request->filters()
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Cash Flow
    |--------------------------------------------------------------------------
    */

    public function cashFlow(
        ReportFilterRequest $request
    ) {
        return new CashFlowResource(
            $this->service->cashFlow(
                $request->filters()
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Expenses
    |--------------------------------------------------------------------------
    */

    public function expenses(
        ReportFilterRequest $request
    ) {
        return new ExpenseReportResource(
            $this->service->expenseReport(
                $request->filters()
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Revenues
    |--------------------------------------------------------------------------
    */

    public function revenues(
        ReportFilterRequest $request
    ) {
        return new RevenueReportResource(
            $this->service->revenueReport(
                $request->filters()
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Project Financial
    |--------------------------------------------------------------------------
    */

    public function projectFinancial(
        int $projectId,
        ReportFilterRequest $request
    ) {
        return new ProjectFinancialResource(
            $this->service->projectFinancial(
                $projectId,
                $request->filters()
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Supplier Financial
    |--------------------------------------------------------------------------
    */

    public function supplierFinancial(
        int $supplierId,
        ReportFilterRequest $request
    ) {
        return new SupplierFinancialResource(
            $this->service->supplierFinancial(
                $supplierId,
                $request->filters()
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Outstanding Expenses
    |--------------------------------------------------------------------------
    */

    public function outstandingExpenses(
        ReportFilterRequest $request
    ) {
        return OutstandingExpenseResource::collection(
            $this->service->outstandingExpenses(
                $request->filters()
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Outstanding Revenues
    |--------------------------------------------------------------------------
    */

    public function outstandingRevenues(
        ReportFilterRequest $request
    ) {
        return OutstandingRevenueResource::collection(
            $this->service->outstandingRevenues(
                $request->filters()
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Cash Transactions
    |--------------------------------------------------------------------------
    */

    public function cashTransactions(
        ReportFilterRequest $request
    ) {
        return CashTransactionResource::collection(
            $this->service->cashTransactions(
                $request->filters(),
                $request->integer('per_page', 20)
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Monthly Financial
    |--------------------------------------------------------------------------
    */

    public function monthlyFinancial(
        ReportFilterRequest $request
    ) {
        return MonthlyFinancialResource::collection(
            $this->service->monthlyFinancial(
                $request->filters()
            )
        );
    }
}
