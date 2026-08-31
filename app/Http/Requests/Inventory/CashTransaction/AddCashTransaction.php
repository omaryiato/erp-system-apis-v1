<?php

namespace App\Http\Requests\Inventory\CashTransaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddCashTransaction extends FormRequest
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
                    'revenue_payment',
                    'expense_payment',
                    'supplier_payment',
                    'employee_payment',
                    'other_income',
                    'other_expense',
                ]),
            ],

            'revenue_id' => [
                'bail',
                'nullable',
                'integer',
                'exists:revenues_v1,id',
                'required_if:transaction_type,revenue_payment',
            ],

            'expense_id' => [
                'bail',
                'nullable',
                'integer',
                'exists:expenses_v1,id',
                'required_if:transaction_type,expense_payment',
            ],

            'supplier_id' => [
                'bail',
                'nullable',
                'integer',
                'exists:suppliers_v1,id',
                'required_if:transaction_type,supplier_payment',
            ],

            'employee_payment_id' => [
                'bail',
                'nullable',
                'integer',
                'exists:employee_payments_v1,id',
                'required_if:transaction_type,employee_payment',
            ],

            'purchase_order_id' => [
                'bail',
                'nullable',
                'integer',
                'exists:purchase_orders_v1,id',
                'required_if:transaction_type,supplier_payment',
            ],

            'project_id' => [
                'bail',
                'nullable',
                'integer',
                'exists:projects_v1,id',
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
