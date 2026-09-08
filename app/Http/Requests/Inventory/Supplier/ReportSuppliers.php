<?php

namespace App\Http\Requests\Inventory\Supplier;

use App\Http\Requests\Base\BaseRequest;

class ReportSuppliers extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from' => [
                'nullable',
                'date',
            ],

            'to' => [
                'nullable',
                'date',
                'after_or_equal:from',
            ],
        ];
    }

    public function filters(): array
    {
        return [
            'from' => $this->input('from'),
            'to' => $this->input('to'),
        ];
    }
}
