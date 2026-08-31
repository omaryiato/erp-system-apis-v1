<?php

namespace App\Http\Requests\Inventory\Supplier;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateSupplier extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('supplier');

        return [
            'supplier_code' => [
                'bail',
                'nullable',
                'string',
                'max:50',
                Rule::unique('suppliers_v1', 'supplier_code')
                    ->ignore($id),
            ],

            'name' => [
                'bail',
                'required',
                'string',
                'max:200',
            ],

            'phone' => [
                'bail',
                'nullable',
                'string',
                'max:30',
            ],

            'email' => [
                'bail',
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'bail',
                'nullable',
                'string',
            ],

            'tax_number' => [
                'bail',
                'nullable',
                'string',
                'max:100',
            ],

            'notes' => [
                'bail',
                'nullable',
                'string',
            ],

            'status' => [
                'bail',
                'required',
                'in:active,inactive',
            ],
        ];
    }
}
