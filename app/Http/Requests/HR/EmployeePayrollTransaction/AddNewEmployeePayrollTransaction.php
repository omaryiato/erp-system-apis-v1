<?php

namespace App\Http\Requests\Hr\EmployeePayrollTransaction;

use Illuminate\Foundation\Http\FormRequest;

class AddNewEmployeePayrollTransaction extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => [
                'required',
                'integer',
                'exists:employees,id',
            ],

            'type' => [
                'required',
                'in:advance,deduction',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'installment_amount' => [
                'nullable',
                'numeric',
                'min:0.01',
            ],

            'transaction_date' => [
                'required',
                'date',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ];
    }
}
