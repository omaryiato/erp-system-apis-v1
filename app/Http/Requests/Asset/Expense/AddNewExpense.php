<?php

namespace App\Http\Requests\Asset\Expense;

use App\Http\Requests\Base\BaseRequest;

class AddNewExpense extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asset_id' => [
                'required',
                'integer',
                'exists:assets_v1,id',
            ],

            'expense_date' => [
                'required',
                'date',
            ],

            'expense_type' => [
                'required',
                'string',
                'max:50',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'supplier_id' => [
                'nullable',
                'integer',
                'exists:suppliers_v1,id',
            ],

            'payment_method' => [
                'nullable',
                'string',
                'max:30',
            ],

            'reference_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}
