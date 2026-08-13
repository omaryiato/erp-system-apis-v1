<?php

namespace App\Http\Requests\Hr\PayrollPeriod;

use Illuminate\Foundation\Http\FormRequest;

class AddNewPayrollPeriod extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
            ],

            'period_start' => [
                'required',
                'date',
            ],

            'period_end' => [
                'required',
                'date',
                'after_or_equal:period_start',
            ],
        ];
    }
}
