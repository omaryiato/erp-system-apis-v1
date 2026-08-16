<?php

namespace App\Http\Requests\Hr\PayrollPeriod;

use App\Helpers\ResponseHelper;
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
                function ($attribute, $value, $fail) {
                    if (!ResponseHelper::isValidDate($value)) {
                        $fail(trans('validation.date_invalid'));
                    }
                }
            ],

            'period_end' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!ResponseHelper::isValidDate($value)) {
                        $fail(trans('validation.date_invalid'));
                    }
                },
                'after_or_equal:period_start',
            ],
        ];
    }
}
