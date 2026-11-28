<?php

namespace App\Http\Requests;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateFinancialAccount extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $accountId = $this->route('financial_account');

        return [
            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'name_ar' => [
                'nullable',
                'string',
                'max:150',
            ],

            'account_type' => [
                'required',
                Rule::in([
                    'BANK',
                    'CASH',
                    'OTHER',
                ]),
            ],

            'account_number' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('financial_accounts_v1', 'account_number')
                        ->ignore($accountId)
                        ->where('id', $this->input('id')),
            ],

            'iban' => [
                'nullable',
                'string',
                'max:100',
                // 'unique:financial_accounts_v1,iban',
                Rule::unique('financial_accounts_v1', 'iban')
                        ->ignore($accountId)
                        ->where('id', $this->input('id')),
            ],

            'bank_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'opening_balance' => [
                'nullable',
                'numeric',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ];
    }
}
