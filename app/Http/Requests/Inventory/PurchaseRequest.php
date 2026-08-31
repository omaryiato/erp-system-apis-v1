<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('purchase');

        return [

            'purchase_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique(
                    'purchase_orders_v1',
                    'purchase_number'
                )->ignore($id),
            ],

            'supplier_id' =>
                'required|integer|exists:suppliers_v1,id',

            'project_id' =>
                'nullable|integer|exists:projects_v1,id',

            'purchase_date' =>
                'required|date',

            'invoice_number' =>
                'nullable|string|max:100',

            'discount_amount' =>
                'nullable|numeric|min:0',

            'tax_amount' =>
                'nullable|numeric|min:0',

            'status' =>
                'sometimes|in:draft,confirmed,received,cancelled',

            'notes' =>
                'nullable|string',

            'items' =>
                'required|array|min:1',

            'items.*.item_id' =>
                'required|integer|exists:items_v1,id',

            'items.*.quantity' =>
                'required|numeric|gt:0',

            'items.*.unit_price' =>
                'required|numeric|min:0',

            'items.*.notes' =>
                'nullable|string',

            'items.*.allocations' =>
                'sometimes|array',

            'items.*.allocations.*.project_id' =>
                'nullable|integer|exists:projects_v1,id',

            'items.*.allocations.*.quantity' =>
                'required|numeric|gt:0',

            'items.*.allocations.*.allocation_type' => [
                'required',
                Rule::in([
                    'project',
                    'unassigned',
                ]),
            ],

            'items.*.allocations.*.notes' =>
                'nullable|string',
        ];
    }
}
