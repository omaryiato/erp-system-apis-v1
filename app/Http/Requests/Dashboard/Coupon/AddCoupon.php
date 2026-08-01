<?php

namespace App\Http\Requests\Dashboard\Coupon;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;


class AddCoupon extends BaseRequest
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

            'code' => [
                'required',
                'string',
                'max:50',
                'unique:coupons,code',
            ],

            'discount_amount' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'minimum_order_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'max_usage' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'used_count' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'expires_at' => [
                'nullable',
                'date',
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
