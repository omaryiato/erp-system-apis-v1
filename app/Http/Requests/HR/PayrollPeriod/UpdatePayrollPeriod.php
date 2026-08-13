<?php

namespace App\Http\Requests\Hr\PayrollPeriod;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePayrollPeriod extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'max:50',
            ],

            'period_start' => [
                'sometimes',
                'date',
            ],

            'period_end' => [
                'sometimes',
                'date',
                'after_or_equal:period_start',
            ],

            'status' => [
                'sometimes',
                'in:draft,processed,paid,locked',
            ],
        ];
    }
}
