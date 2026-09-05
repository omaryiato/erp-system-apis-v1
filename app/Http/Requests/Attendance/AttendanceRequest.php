<?php

namespace App\Http\Requests\Attendance;

use App\Http\Requests\Base\BaseRequest;


class AttendanceRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }


public function rules(): array
{
    return [
        'attendances' => [
            'required',
            'array',
            'min:1',
        ],

        'attendances.*.employee_id' => [
            'required',
            'integer',
            'exists:employees_v1,id',
        ],

        'attendances.*.work_date' => [
            'required',
            'date',
        ],

        'attendances.*.check_in' => [
            'nullable',
            'date',
        ],

        'attendances.*.check_out' => [
            'nullable',
            'date',
            'after_or_equal:attendances.*.check_in',
        ],

        'attendances.*.worked_hours' => [
            'nullable',
            'numeric',
            'min:0',
            'max:24',
        ],

        'attendances.*.overtime_hours' => [
            'nullable',
            'numeric',
            'min:0',
            'max:24',
        ],

        'attendances.*.notes' => [
            'nullable',
            'string',
        ],
    ];
}



}
