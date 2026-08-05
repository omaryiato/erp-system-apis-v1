<?php

namespace App\Http\Requests\HR\Attendance;

use App\Helpers\ResponseHelper;
use Illuminate\Foundation\Http\FormRequest;



class AddNewAttendance extends FormRequest
{


public function authorize()
{
    return true;
}




public function rules()
{

    return [


        'employee_id'=> [
            'bail',
            'required',
            'exists:employees,id'
        ],

        'work_date' => [
            'bail',
            'required',
            function ($attribute, $value, $fail) {
                    if (!ResponseHelper::isValidDate($value)) {
                        $fail(trans('validation.date_invalid'));
                    }
                }
        ],

        'check_in' => [
            'bail',
            'nullable',
            function ($attribute, $value, $fail) {
                    if (!ResponseHelper::isValidDate($value)) {
                        $fail(trans('validation.date_invalid'));
                    }
                }
        ],

        'check_out' => [
            'bail',
            'nullable',
            function ($attribute, $value, $fail) {
                    if (!ResponseHelper::isValidDate($value)) {
                        $fail(trans('validation.date_invalid'));
                    }
                },
            'after_or_equal:check_in'
        ],

        'source' => [
            'bail',
            'required',
            'in:manual,device,mobile,system'
        ],

        'status' => [
            'bail',
            'required',
            'in:present,absent,late,half_day,on_leave',
        ],

        'late_minutes' => [
            'bail',
            'nullable',
            'integer',
            'min:0',
        ],

        'notes' => [
            'bail',
            'nullable',
            'string',
        ],

        'created_by' => [
            'bail',
            'nullable',
            'integer',
        ],

    ];


}


}
