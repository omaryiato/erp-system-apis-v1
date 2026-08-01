<?php

namespace App\Http\Requests\Dashboard\Address;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;


class AddAddress extends BaseRequest
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
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'label' => [
                'nullable',
                'string',
                'max:50',
            ],

            'full_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'address_line' => [
                'required',
                'string',
                'max:500',
            ],

            'city' => [
                'required',
                'string',
                'max:100',
            ],

            'country' => [
                'required',
                'string',
                'max:100',
            ],

            'postal_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'is_default' => [
                'nullable',
                'integer',
                'in:0,1',
            ],
        ];
    }
}
