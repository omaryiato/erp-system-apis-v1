<?php

namespace App\Http\Requests\Asset\Category;

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
        $id = $this->route('id');

        return [
            'category_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique(
                    'asset_categories_v1',
                    'category_code'
                )->ignore($id),
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
