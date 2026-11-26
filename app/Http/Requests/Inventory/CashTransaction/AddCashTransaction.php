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

            'revenue_id' => [
                'bail',
                'nullable',
                'integer',
                'exists:revenues_v1,id',
                'required_if:transaction_type,revenue',
            ],

            'expense_id' => [
                'bail',
                'nullable',
                'integer',
                'exists:expenses_v1,id',
                'required_if:transaction_type,expense',
            ],

            // 'supplier_id' => [
            //     'bail',
            //     'nullable',
            //     'integer',
            //     'exists:suppliers_v1,id',
            //     'required_if:transaction_type,supplier',
            // ],

            'employee_payment_id' => [
                'bail',
                'nullable',
                'integer',
                'exists:employee_payments_v1,id',
                'required_if:transaction_type,employee',
            ],

            'purchase_id' => [
                'bail',
                'nullable',
                'integer',
                'exists:purchase_orders_v1,id',
                'required_if:transaction_type,supplier',
            ],

            // 'project_id' => [
            //     'bail',
            //     'nullable',
            //     'integer',
            //     'exists:projects_v1,id',
            // ],

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
