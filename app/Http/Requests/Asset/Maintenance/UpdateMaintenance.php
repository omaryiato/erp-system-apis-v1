<?php

namespace App\Http\Requests\Asset\Maintenance;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateMaintenance extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asset_id' => [
                'sometimes',
                'integer',
                'exists:assets_v1,id',
            ],

            'maintenance_date' => [
                'sometimes',
                'date',
            ],

            'maintenance_type' => [
                'sometimes',
                'string',
                'max:50',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'supplier_id' => [
                'nullable',
                'integer',
                'exists:suppliers_v1,id',
            ],

            'cost' => [
                'sometimes',
                'numeric',
                'min:0',
            ],

            'next_maintenance_date' => [
                'nullable',
                'date',
                'after:maintenance_date',
            ],

            'status' => [
                'sometimes',
                Rule::in([
                    'PENDING',
                    'IN_PROGRESS',
                    'COMPLETED',
                    'CANCELLED',
                ]),
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}
