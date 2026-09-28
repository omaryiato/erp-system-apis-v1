<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinancialAccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /*
        |--------------------------------------------------------------------------
        | Revenues
        |--------------------------------------------------------------------------
        | Cash transactions that have revenue_id
        */
        $revenues = $this->cashTransactions
            ->whereNotNull('revenue_id')
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Cash Expenses
        |--------------------------------------------------------------------------
        | Cash transactions that have expense_id
        */
        $cashExpenses = $this->cashTransactions
            ->whereNotNull('expense_id')
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Other Expenses
        |--------------------------------------------------------------------------
        */
        // $chequeExpenses = $this->cheques->sum('amount');

        $purchaseExpenses = $this->purchaseItems->sum('total_amount');

        $employeePaymentExpenses = $this->employeePayments->sum('amount');

        $assetExpenses = $this->assetExpenses->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Total Expenses
        |--------------------------------------------------------------------------
        */
        $totalExpenses =
            $cashExpenses
            // + $chequeExpenses
            + $purchaseExpenses
            + $employeePaymentExpenses
            + $assetExpenses;

        /*
        |--------------------------------------------------------------------------
        | Current Balance
        |--------------------------------------------------------------------------
        */
        $currentBalance =
            $this->opening_balance
            + $revenues
            - $totalExpenses;


        return [
            'id' => $this->id,

            'name' => $this->name,
            'name_ar' => $this->name_ar,

            'account_type' => $this->account_type,

            'account_number' => $this->account_number,
            'iban' => $this->iban,
            'bank_name' => $this->bank_name,

            'currency' => $this->currency,

            'opening_balance' => $this->opening_balance,

            'current_balance' => $currentBalance,

            'is_active' => $this->is_active,

            'description' => $this->description,

            /*
            |--------------------------------------------------------------------------
            | Financial Summary
            |--------------------------------------------------------------------------
            */
            'financial_summary' => [
                'revenues' => $revenues,

                'expenses' => [
                    'cash' => $cashExpenses,
                    // 'cheques' => $chequeExpenses,
                    'purchases' => $purchaseExpenses,
                    'employee_payments' => $employeePaymentExpenses,
                    'asset_expenses' => $assetExpenses,
                    'total' => $totalExpenses,
                ],

                // 'current_balance' => $currentBalance,
            ],

            'cheques' => ChequeResource::collection($this->whenLoaded('cheques')),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
