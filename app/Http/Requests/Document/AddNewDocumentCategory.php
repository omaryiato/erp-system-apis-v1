<?php

namespace App\Http\Requests\Document;

use App\Http\Requests\Base\BaseRequest;

class AddNewDocumentCategory extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

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
                'unique:document_categories_v1,code',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
