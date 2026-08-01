<?php

namespace App\Http\Requests\Dashboard\LookupValue;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;



class AddLookupValue extends BaseRequest
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
            'type_id' => [
                'required',
                'integer',
                'exists:lookup_types,id',
            ],

            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('lookup_values')
                    ->where(fn ($query) => $query->where('type_id', $this->type_id)),
            ],

            'en_meaning' => [
                'required',
                'string',
                'max:255',
            ],

            'ar_meaning' => [
                'required',
                'string',
                'max:255',
            ],

            'color' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:500',
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
