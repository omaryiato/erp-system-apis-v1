<?php

namespace App\Http\Requests\HR\Employee;

use App\Helpers\ResponseHelper;
use Illuminate\Foundation\Http\FormRequest;



class UpdateEmployee extends FormRequest
{

public function authorize()
{
    return true;
}



public function rules()
{


    return [

        'employee_id_number' => [
            'bail',
            'required',
            'string',
            'max:30',
            'unique:employees'
        ],

        'full_name' => [
            'bail',
            'required',
            'string',
            'max:30',
        ],

        'national_id' => [
            'bail',
            'nullable',
            'string',
            'max:50',
        ],

        'department_id' => [
            'bail',
            'nullable',
            'exists:departments,id',
        ],

        'position_id' => [
            'bail',
            'nullable',
            'exists:positions,id',
        ],


        'hire_date' => [
            'bail',
            'required',
            function ($attribute, $value, $fail) {
                    if (!ResponseHelper::isValidDate($value)) {
                        $fail(trans('validation.date_invalid'));
                    }
                },
        ],

        'termination_date' => [
            'bail',
            'nullable',
            function ($attribute, $value, $fail) {
                    if (!ResponseHelper::isValidDate($value)) {
                        $fail(trans('validation.date_invalid'));
                    }
                },
        ],

        'status' => [
            'bail',
            'required',
            'in:active,suspended,terminated,on_leave',
        ],

        'base_salary' => [
            'bail',
            'required',
            'numeric',
            'min:0',
        ],

        'shift_id' => [
            'bail',
            'nullable',
            'integer',
        ],

        'biometric_code' => [
            'bail',
            'nullable',
            'string',
            'max:50',
        ],

    ];


}


}
