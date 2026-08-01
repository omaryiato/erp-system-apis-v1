<?php

namespace App\Http\Requests\Dashboard\ShippingMethod;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;



class UpdateShippingMethod extends BaseRequest
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
        $id = $this->route("method_id");
        return [

            'method_en_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('shipping_methods', 'method_en_name')
                        ->ignore($id)
                        ->where('id', $this->input('id')),
            ],

            'method_ar_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('shipping_methods', 'method_ar_name')
                        ->ignore($id)
                        ->where('id', $this->input('id')),
            ],

            'price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'estimated_days' => [
                'nullable',
                'integer',
                'min:0',
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
