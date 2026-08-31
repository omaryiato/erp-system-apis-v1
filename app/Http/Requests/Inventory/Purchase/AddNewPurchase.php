<?php

namespace App\Http\Requests\Inventory\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class AddNewPurchase extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => [
                'bail',
                'required',
                'integer',
                'exists:suppliers_v1,id',
            ],

            'purchase_date' => [
                'bail',
                'required',
                'date',
            ],

            'reference_number' => [
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
                'nullable',
                'in:draft,confirmed,cancelled',
            ],

            'items' => [
                'bail',
                'required',
                'array',
                'min:1',
            ],

            'items.*.item_id' => [
                'bail',
                'required',
                'integer',
                'exists:items_v1,id',
            ],

            'items.*.quantity' => [
                'bail',
                'required',
                'numeric',
                'gt:0',
            ],

            'items.*.unit_price' => [
                'bail',
                'required',
                'numeric',
                'gte:0',
            ],

            'items.*.notes' => [
                'bail',
                'nullable',
                'string',
            ],

            'items.*.allocations' => [
                'nullable',
                'array',
            ],

            'items.*.allocations.*.project_id' => [
                'bail',
                'required',
                'integer',
                'exists:projects_v1,id',
            ],

            'items.*.allocations.*.quantity' => [
                'bail',
                'required',
                'numeric',
                'gt:0',
            ],

            'items.*.allocations.*.notes' => [
                'bail',
                'nullable',
                'string',
            ],
        ];
    }
}
