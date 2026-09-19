<?php

namespace App\Http\Requests\Attendance;

use App\Http\Requests\Base\BaseRequest;


class UpdateAttendance extends BaseRequest
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
            'integer',
            'exists:employees_v1,id',
        ],

        'work_date' => [
            'required',
            'date',
        ],

        'check_in' => [
            'nullable',
            'date',
        ],

        'check_out' => [
            'nullable',
            'date',
            'after_or_equal:check_in',
        ],

        'worked_hours' => [
            'nullable',
            'numeric',
            'min:0',
            'max:24',
        ],

        'overtime_hours' => [
            'nullable',
            'numeric',
            'min:0',
            'max:24',
        ],

        'notes' => [
            'nullable',
            'string',
        ],

        'updated_by' => [
            'required',
            'integer',
        ],
    ];
}



}
