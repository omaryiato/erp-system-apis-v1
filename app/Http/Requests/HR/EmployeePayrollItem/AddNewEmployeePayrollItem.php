<?php

namespace App\Http\Requests\HR\EmployeePayrollItem;

use App\Helpers\ResponseHelper;
use Illuminate\Foundation\Http\FormRequest;



class AddNewEmployeePayrollItem extends FormRequest
{


public function authorize()
{
    return true;
}




public function rules()
{


    return [


        'employee_id' => [
            'bail',
            'required',
            'exists:employees,id',
        ],

        'payroll_item_type_id' => [
            'bail',
            'required',
            'exists:payroll_item_types,id',
        ],

        'value' => [
            'bail',
            'required',
            'numeric',
            'min:0',
        ],

        'effective_from' => [
            'bail',
            'required',
            function ($attribute, $value, $fail) {
                    if (!ResponseHelper::isValidDate($value)) {
                        $fail(trans('validation.date_invalid'));
                    }
                },
        ],

        // 'effective_to'=>
        // 'nullable|date|after_or_equal:effective_from'

    ];


}



}
