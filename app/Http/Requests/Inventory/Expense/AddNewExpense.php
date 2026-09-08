<?php

namespace App\Http\Requests\Inventory\Expense;

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
            'expense_number' => [
                'bail',
                'required',
                'string',
                'max:50',
                'unique:expenses_v1,expense_number',
            ],

            'expense_date' => [
                'bail',
                'required',
                'date',
            ],

            'category_id' => [
                'bail',
                'required',
                'integer',
                'exists:expenses_categories_v1,id',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'amount' => [
                'bail',
                'required',
                'numeric',
                'gt:0',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}
