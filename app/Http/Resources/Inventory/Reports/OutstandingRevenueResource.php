<?php

namespace App\Http\Resources\Inventory\Reports;

use App\Http\Resources\Inventory\RevenueResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OutstandingRevenueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'revenue' =>
                new RevenueResource(
                    $this->resource['revenue']
                ),

            'amount' =>
                $this->resource['amount'],

            'received_amount' =>
                $this->resource['received_amount'],

            'remaining_amount' =>
                $this->resource['remaining_amount'],
        ];
    }
}
