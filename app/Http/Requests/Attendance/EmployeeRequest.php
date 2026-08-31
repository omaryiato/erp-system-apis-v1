<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Validation\Rule;
use App\Http\Requests\Base\BaseRequest;


class EmployeeRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employee = $this->route('employee');

        return [
            'employee_number' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('employees_v1', 'employee_number')
                    ->ignore($employee?->id),
            ],

            'full_name' => [
                'required',
                'string',
                'max:200',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'salary_type' => [
                'required',
                Rule::in(['daily', 'monthly']),
            ],

            'base_salary' => [
                'required',
                'numeric',
                'min:0',
            ],

            'overtime_rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'status' => [
                'nullable',
                Rule::in(['active', 'inactive']),
            ],
        ];
    }
}
