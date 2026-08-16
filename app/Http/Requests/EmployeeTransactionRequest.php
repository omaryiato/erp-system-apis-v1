<?php

namespace App\Http\Requests;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Validation\Rule;

class EmployeeTransactionRequest extends BaseRequest
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

            'type' => [
                'required',
                Rule::in([
                    'advance',
                    'deduction',
                ]),
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
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
