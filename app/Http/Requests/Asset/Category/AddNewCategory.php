<?php

namespace App\Http\Requests\Asset\Category;

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
            'category_code' => [
                'required',
                'string',
                'max:50',
                'unique:asset_categories_v1,category_code',
            ],

            'category_name' => [
                'required',
                'string',
                'max:150',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
