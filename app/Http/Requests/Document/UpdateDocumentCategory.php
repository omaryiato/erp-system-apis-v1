<?php

namespace App\Http\Requests\Document;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Validation\Rule;


class UpdateDocumentCategory extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

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
                Rule::unique(
                    'document_categories_v1',
                    'code'
                )->ignore($id),
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
