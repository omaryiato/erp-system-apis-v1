<?php

namespace App\Http\Requests\Document;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Validation\Rule;

class AddNewDocument extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'integer',
                'exists:document_categories_v1,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'document_code' => [
                'nullable',
                'string',
                'max:100',
                'unique:documents_v1,document_code',
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'ACTIVE',
                    'ARCHIVED',
                    'DELETED',
                ]),
            ],

            'reference_type' => [
                'nullable',
                'string',
                'max:50',
            ],

            'reference_id' => [
                'nullable',
                'integer',
            ],

            'file' => [
                'required',
                'file',
                'max:51200',
            ],

            'created_by' => [
                'required',
                'integer',
                'exists:users_v1,id'
            ],

            'updated_by' => [
                'required',
                'integer',
                'exists:users_v1,id'
            ],
        ];
    }
}
