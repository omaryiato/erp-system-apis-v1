<?php

namespace App\Http\Resources\Inventory\Reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinancialSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total_revenues' =>
                $this->resource['total_revenues'],

            'total_expenses' =>
                $this->resource['total_expenses'],

            'total_received' =>
                $this->resource['total_received'],

            'total_paid' =>
                $this->resource['total_paid'],

            'net_profit' =>
                $this->resource['net_profit'],

            'net_cash_flow' =>
                $this->resource['net_cash_flow'],
        ];
    }
}
