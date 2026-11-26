<?php

namespace App\Http\Requests\Inventory\CashTransaction;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Validation\Rule;

class AddCashTransaction extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $referenceTable = match ($this->input('transaction_type')) {
            'expense' => 'expenses_v1',
            'revenue' => 'revenues_v1',
            'purchase' => 'purchases_v1',
            default => null,
        };

        return [
            'transaction_type' => [
                'bail',
                'required',
                'string',
                Rule::in([
                    'revenue',
                    'expense',
                    // 'supplier',
                    // 'employee',
                    // 'other_income',
                    // 'other_expense',
                    'purchase',
                ]),
            ],

            'reference_id' => [
                'bail',
                'required',
                'integer',
                'min:1',
                ...($referenceTable === null ? [] : [Rule::exists($referenceTable, 'id')]),
            ],

            'payment_method' => [
                'bail',
                'required',
                'string',
                'in:cash,cheques,debts,bank_transfer',
            ],

            'financial_account_id' => [
                'bail',
                'required',
                'integer',
                'exists:financial_accounts_v1,id',
            ],

            'amount' => [
                'bail',
                'required',
                'numeric',
                'decimal:0,2',
                'gt:0',
            ],

            'transaction_date' => [
                'bail',
                'required',
                'date',
            ],

            'description' => [
                'bail',
                'nullable',
                'string',
            ],

            'notes' => [
                'bail',
                'nullable',
                'string',
            ],
        ];
    }
}
