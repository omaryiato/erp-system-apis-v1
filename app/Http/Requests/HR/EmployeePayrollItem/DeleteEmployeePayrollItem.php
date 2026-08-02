<?php

namespace App\Http\Requests\HR\EmployeePayrollItem;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class DeleteEmployeePayroll extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => [
                'bail',
                'required',
                'integer',
                'exists:employee_payroll_items,id'
            ],
        ];
    }
}
