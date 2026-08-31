<?php

namespace App\Http\Resources\Inventory\Reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierFinancialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $supplier = $this->resource['supplier'];

        return [
            'supplier' => [
                'id' => $supplier->id,
                'name' => $supplier->name,
            ],

            'total_expenses' =>
                $this->resource['total_expenses'],

            'total_paid' =>
                $this->resource['total_paid'],

            'outstanding' =>
                $this->resource['outstanding'],
        ];
    }
}
