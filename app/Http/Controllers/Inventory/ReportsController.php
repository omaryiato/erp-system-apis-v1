<?php

namespace App\Http\Controllers\Inventory;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\Reports\OperationReport;
use App\Http\Requests\Inventory\Reports\ReportFilterRequest;
use App\Http\Resources\Inventory\CashTransactionResource;
use App\Http\Resources\Inventory\Reports\CashFlowResource;
use App\Http\Resources\Inventory\Reports\FinancialSummaryResource;
use App\Http\Resources\Inventory\Reports\MonthlyFinancialResource;
use App\Http\Resources\Inventory\Reports\OperationReportResource;
use App\Http\Resources\Inventory\Reports\OutstandingExpenseResource;
use App\Http\Resources\Inventory\Reports\OutstandingRevenueResource;
use App\Services\Inventory\ReportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReportsController extends Controller
{
    public function __construct(
        private ReportService $service
    ) {}


    /*
    |--------------------------------------------------------------------------
    | Operation Report
    |--------------------------------------------------------------------------
    */

    public function operationReport(
        OperationReport $request
    ) {
        return ResponseHelper::success(
                new OperationReportResource(
                    $this->service->operationReport(
                        $request->filters()
                    )
                ),
                [
                    'en' => trans('validation.get_revenue_details', [], 'en'),
                    'ar' => trans('validation.get_revenue_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }



    /*
    |--------------------------------------------------------------------------
    | Financial Summary
    |--------------------------------------------------------------------------
    */



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
