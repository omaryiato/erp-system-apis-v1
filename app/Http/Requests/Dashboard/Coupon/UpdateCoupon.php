<?php

namespace App\Http\Requests\Dashboard\Coupon;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;



class UpdateCoupon extends BaseRequest
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
        $id = $this->route("category_id");


        return [

            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('coupons', 'code')
                        ->ignore($id)
                        ->where('id', $this->input('id')),
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

            'updated_by' => [
                'required',
                'integer',
                'exists:users,id'
            ],
        ];
    }
}
