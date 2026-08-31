<?php

namespace App\Http\Requests\Inventory\Revenue;

use Illuminate\Foundation\Http\FormRequest;

class AddNewRevenue extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'revenue_number' => [
                'bail',
                'required',
                'string',
                'max:50',
                'unique:revenues_v1,revenue_number',
            ],

            'project_id' => [
                'bail',
                'nullable',
                'integer',
                'exists:projects_v1,id',
            ],

            'revenue_date' => [
                'bail',
                'required',
                'date',
            ],

            'category' => [
                'bail',
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'amount' => [
                'bail',
                'required',
                'numeric',
                'gt:0',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}
