<?php

namespace App\Http\Requests\HR\Payslip;

use Illuminate\Foundation\Http\FormRequest;

class AddNewPayslip extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payroll_period_id' => [
                'required',
                'integer',
                'exists:payroll_periods,id',
            ],

            'employee_id' => [
                'required',
                'integer',
                'exists:employees,id',
            ],

            'base_salary' => [
                'required',
                'numeric',
                'min:0',
            ],

            'total_allowances' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'total_deductions' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'net_salary' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'nullable',
                'in:draft,approved,paid',
            ],
        ];
    }
}
