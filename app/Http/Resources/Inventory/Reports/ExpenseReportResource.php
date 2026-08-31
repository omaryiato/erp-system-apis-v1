<?php

namespace App\Http\Resources\Inventory\Reports;

use App\Http\Resources\Inventory\ExpenseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total' =>
                $this->resource['total'],

            'paid' =>
                $this->resource['paid'],

            'outstanding' =>
                $this->resource['outstanding'],

            'count' =>
                $this->resource['count'],

            'expenses' =>
                ExpenseResource::collection(
                    $this->resource['expenses']
                ),
        ];
    }
}
