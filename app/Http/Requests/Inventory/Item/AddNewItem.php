<?php

namespace App\Http\Requests\Inventory\Item;

use App\Http\Requests\Base\BaseRequest;

class AddNewItem extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'bail',
                'required',
                'integer',
                'exists:categories_v1,id',
            ],

            'name' => [
                'bail',
                'required',
                'string',
                'max:200',
            ],

            'code' => [
                'bail',
                'nullable',
                'string',
                'max:50',
                'unique:items_v1,code',
            ],

            'unit' => [
                'bail',
                'required',
                'string',
                'max:30',
            ],

            'description' => [
                'bail',
                'nullable',
                'string',
            ],

            'current_unit_price' => [
                'bail',
                'required',
                'numeric',
                'min:0',
            ],

            'minimum_stock' => [
                'bail',
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'bail',
                'sometimes',
                'in:active,inactive',
            ],
        ];
    }
}
