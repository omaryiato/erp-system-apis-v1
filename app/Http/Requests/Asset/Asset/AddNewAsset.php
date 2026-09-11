<?php

namespace App\Http\Requests\Asset\Asset;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Validation\Rule;

class AddNewAsset extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asset_code' => [
                'required',
                'string',
                'max:50',
                'unique:assets_v1,asset_code',
            ],

            'asset_name' => [
                'required',
                'string',
                'max:200',
            ],

            'category_id' => [
                'required',
                'integer',
                'exists:asset_categories_v1,id',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'serial_number' => [
                'nullable',
                'string',
                'max:150',
            ],

            'model' => [
                'nullable',
                'string',
                'max:150',
            ],

            'manufacturer' => [
                'nullable',
                'string',
                'max:150',
            ],

            'purchase_date' => [
                'nullable',
                'date',
            ],

            'purchase_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'sometimes',
                Rule::in([
                    'ACTIVE',
                    'IN_MAINTENANCE',
                    'DAMAGED',
                    'DISPOSED',
                    'LOST',
                ]),
            ],

            'location' => [
                'nullable',
                'string',
                'max:200',
            ],

            'warranty_expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:purchase_date',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}
