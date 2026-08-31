<?php

namespace App\Http\Requests\Inventory\Category;

use App\Http\Requests\Base\BaseRequest;


class AddNewCategory extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'bail',
                'required',
                'string',
                'max:150',
                'unique:categories_v1,name',
            ],

            'description' => [
                'bail',
                'nullable',
                'string',
            ],

            'status' => [
                'bail',
                'sometimes',
                'in:active,inactive',
            ],
        ];
    }
}
