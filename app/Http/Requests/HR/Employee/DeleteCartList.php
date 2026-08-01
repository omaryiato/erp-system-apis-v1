<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class DeleteCartList extends BaseRequest
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
                'bail',
                'required',
                'integer',
                'exists:users,id'
            ],
            'cart_id' => [
                'bail',
                'required',
                'integer',
                'exists:cart_products,id'
            ],
        ];
    }
}
