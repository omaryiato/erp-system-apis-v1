<?php

namespace App\Http\Requests\Inventory\Expense;

use Illuminate\Foundation\Http\FormRequest;

class AddNewExpense extends FormRequest
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

            'project_id' => [
                'bail',
                'nullable',
                'integer',
                'exists:projects_v1,id',
            ],

            'supplier_id' => [
                'bail',
                'nullable',
                'integer',
                'exists:suppliers_v1,id',
            ],

            'expense_date' => [
                'bail',
                'required',
                'date',
            ],

            'category' => [
                'bail',
                'required',
                'string',
                'max:100',
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
