<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'transaction_number' =>
                'required|string|max:50|unique:cash_transactions_v1,transaction_number',

            'amount' =>
                'required|numeric|gt:0',

            'transaction_date' =>
                'required|date',

            'description' =>
                'nullable|string',

            'notes' =>
                'nullable|string',
        ];
    }
}
