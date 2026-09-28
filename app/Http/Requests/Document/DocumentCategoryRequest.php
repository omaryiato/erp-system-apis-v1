<?php

namespace App\Http\Requests\Document;

use App\Http\Requests\Base\BaseRequest;

class DocumentCategoryRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id' => [
                'nullable',
                'integer',
                'exists:document_categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'name_ar' => [
                'nullable',
                'string',
                'max:150',
            ],

            'code' => [
                'required',
                'string',
                'max:50',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }
}
