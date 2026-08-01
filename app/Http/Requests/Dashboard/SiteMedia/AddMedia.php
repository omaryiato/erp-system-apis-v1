<?php

namespace App\Http\Requests\Dashboard\SiteMedia;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;


class AddMedia extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'type' => [
                'required',
                'string',
                'max:50',
            ],

            'status' => [
                'nullable',
                'integer',
                'in:0,1',
            ],

            'created_by' => [
                'required',
                'integer',
                'exists:users,id'
            ],

            'updated_by' => [
                'required',
                'integer',
                'exists:users,id'
            ],
        ];
    }
}
