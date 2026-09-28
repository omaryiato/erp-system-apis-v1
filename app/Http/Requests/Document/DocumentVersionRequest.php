<?php

namespace App\Http\Requests\Document;

use App\Http\Requests\Base\BaseRequest;

class DocumentVersionRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:51200',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ];
    }
}
