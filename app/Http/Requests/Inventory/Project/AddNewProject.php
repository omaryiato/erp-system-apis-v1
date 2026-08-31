<?php

namespace App\Http\Requests\Inventory\Project;

use App\Http\Requests\Base\BaseRequest;

class AddNewProject extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_code' => [
                'bail',
                'nullable',
                'string',
                'max:50',
                'unique:projects_v1,project_code',
            ],

            'project_name' => [
                'bail',
                'required',
                'string',
                'max:200',
            ],

            'customer_name' => [
                'bail',
                'required',
                'string',
                'max:200',
            ],

            'phone' => [
                'bail',
                'nullable',
                'string',
                'max:30',
            ],

            'email' => [
                'bail',
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'bail',
                'nullable',
                'string',
            ],

            'description' => [
                'bail',
                'nullable',
                'string',
            ],

            'start_date' => [
                'bail',
                'nullable',
                'date',
            ],

            'end_date' => [
                'bail',
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'status' => [
                'bail',
                'sometimes',
                'in:active,completed,cancelled,inactive',
            ],
        ];
    }
}
