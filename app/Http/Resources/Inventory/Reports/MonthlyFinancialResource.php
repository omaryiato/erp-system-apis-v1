<?php

namespace App\Http\Resources\Inventory\Reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthlyFinancialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'month' =>
                $this->resource['month'],

            'revenue' =>
                $this->resource['revenue'],

            'expense' =>
                $this->resource['expense'],

            'net' =>
                $this->resource['net'],
        ];
    }
}
