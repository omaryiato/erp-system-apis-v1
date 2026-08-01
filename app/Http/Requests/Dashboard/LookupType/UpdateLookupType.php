<?php

namespace App\Http\Requests\Dashboard\LookupType;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;


class UpdateLookupType extends BaseRequest
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
        $id = $this->route("lookupValue");
        return [
            'type_en_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('lookup_types', 'type_en_name')
                        ->ignore($id)
                        ->where('id', $this->input('id')),
            ],

            'type_ar_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('lookup_types', 'type_ar_name')
                        ->ignore($id)
                        ->where('id', $this->input('id')),
            ],

            'type_description' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'nullable',
                'integer',
                'in:0,1',
            ],

            'updated_by' => [
                'required',
                'integer',
                'exists:users,id'
            ],
        ];
    }
}
