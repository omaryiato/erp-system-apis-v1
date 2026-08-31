<?php

namespace App\Http\Resources\Inventory\Reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CashFlowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total_income' =>
                $this->resource['total_income'],

            'total_expense' =>
                $this->resource['total_expense'],

            'net_cash_flow' =>
                $this->resource['net_cash_flow'],
        ];
    }
}
