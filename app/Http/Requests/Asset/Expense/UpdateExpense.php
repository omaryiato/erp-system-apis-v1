<?php

namespace App\Http\Requests\Asset\Expense;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExpense extends BaseRequest
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

            'expense_date' => [
                'sometimes',
                'date',
            ],

            'expense_type' => [
                'sometimes',
                'string',
                'max:50',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'amount' => [
                'sometimes',
                'numeric',
                'gt:0',
            ],

            'supplier_id' => [
                'nullable',
                'integer',
                'exists:suppliers_v1,id',
            ],

            'payment_method' => [
                'nullable',
                'string',
                'max:30',
            ],

            'reference_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}
