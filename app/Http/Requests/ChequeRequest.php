<?php

namespace App\Http\Requests;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Validation\Rule;

class ChequeRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cheque_number' => [
                'required',
                'string',
                'max:100',
            ],

            'cheque_type' => [
                'required',
                Rule::in([
                    'ISSUED',
                    'RECEIVED',
                ]),
            ],

            'financial_account_id' => [
                'required',
                'integer',
                'exists:financial_accounts,id',
            ],

            'party_type' => [
                'nullable',
                'string',
                'max:30',
            ],

            'party_id' => [
                'nullable',
                'integer',
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'issue_date' => [
                'required',
                'date',
            ],

            'due_date' => [
                'nullable',
                'date',
                'after_or_equal:issue_date',
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'PENDING',
                    'DEPOSITED',
                    'CLEARED',
                    'BOUNCED',
                    'CANCELLED',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ];
    }
}
