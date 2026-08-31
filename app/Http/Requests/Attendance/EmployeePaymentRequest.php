<?php

namespace App\Http\Requests\Attendance;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Validation\Rule;

class EmployeePaymentRequest extends BaseRequest
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
                'exists:employees_v1,id',
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'payment_type' => [
                'required',
                Rule::in([
                    'salary',
                    'advance',
                    'other',
                ]),
            ],

            'period_start' => [
                'nullable',
                'date',
            ],

            'period_end' => [
                'nullable',
                'date',
                'after_or_equal:period_start',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}
