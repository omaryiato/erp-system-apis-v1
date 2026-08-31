<?php

namespace App\Http\Requests\Inventory\Category;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateCategory extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('category');

        return [
            'name' => [
                'bail',
                'required',
                'string',
                'max:150',
                Rule::unique('categories_v1', 'name')
                    ->ignore($id),
            ],

            'description' => [
                'bail',
                'nullable',
                'string',
            ],

            'status' => [
                'bail',
                'required',
                'in:active,inactive',
            ],
        ];
    }
}
