<?php

namespace App\Http\Requests\Hr\PayrollPeriod;

use App\Helpers\ResponseHelper;
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
                function ($attribute, $value, $fail) {
                    if (!ResponseHelper::isValidDate($value)) {
                        $fail(trans('validation.date_invalid'));
                    }
                },
            ],

            'period_end' => [
                'sometimes',
                function ($attribute, $value, $fail) {
                    if (!ResponseHelper::isValidDate($value)) {
                        $fail(trans('validation.date_invalid'));
                    }
                },
                'after_or_equal:period_start',
            ],

            'status' => [
                'sometimes',
                'in:draft,processed,paid,locked',
            ],
        ];
    }
}
