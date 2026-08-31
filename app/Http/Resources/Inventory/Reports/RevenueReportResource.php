<?php

namespace App\Http\Resources\Inventory\Reports;

use App\Http\Resources\Inventory\RevenueResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RevenueReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total' =>
                $this->resource['total'],

            'received' =>
                $this->resource['received'],

            'outstanding' =>
                $this->resource['outstanding'],

            'count' =>
                $this->resource['count'],

            'revenues' =>
                RevenueResource::collection(
                    $this->resource['revenues']
                ),
        ];
    }
}
