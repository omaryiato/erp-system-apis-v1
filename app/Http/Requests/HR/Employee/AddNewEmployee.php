<?php

namespace App\Http\Requests\HR\Employee;


use Illuminate\Foundation\Http\FormRequest;


class AddNewEmployee extends FormRequest
{

    public function authorize()
    {
        return true;
    }



    public function rules()
    {

        return [

            'employee_id_number'
                =>'required|string|max:30|unique:employees',

            'full_name'
                =>'required|string|max:200',

            'national_id'
                =>'nullable|string|max:50',

            'department_id'
                =>'nullable|exists:departments,id',

            'position_id'
                =>'nullable|exists:positions,id',


            'hire_date'
                =>'required|date',

            'termination_date'
                =>'nullable|date',


            'status'
                =>'nullable|in:active,suspended,terminated,on_leave',


            'base_salary'
                =>'required|numeric|min:0',


            'shift_id'
                =>'nullable|integer',

            'biometric_code'
                =>'nullable|string|max:50'

        ];

    }

}
