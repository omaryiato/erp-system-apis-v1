<?php

namespace App\Http\Requests\Inventory\Reports;

use Illuminate\Foundation\Http\FormRequest;

class ReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from' => [
                'nullable',
                'date',
            ],

            'to' => [
                'nullable',
                'date',
                'after_or_equal:from',
            ],

            'project_id' => [
                'nullable',
                'integer',
                'exists:projects_v1,id',
            ],

            'supplier_id' => [
                'nullable',
                'integer',
                'exists:suppliers_v1,id',
            ],

            'transaction_type' => [
                'nullable',
                'string',
                'in:income,expense,supplier_payment,employee_payment,other_income,other_expense',
            ],

            'category' => [
                'nullable',
                'string',
                'max:100',
            ],

            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:100',
            ],
        ];
    }

    public function filters(): array
    {
        return [
            'from' => $this->input('from'),
            'to' => $this->input('to'),
            'project_id' => $this->input('project_id'),
            'supplier_id' => $this->input('supplier_id'),
            'transaction_type' => $this->input('transaction_type'),
            'category' => $this->input('category'),
        ];
    }
}
