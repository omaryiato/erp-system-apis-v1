<?php

// namespace App\Http\Resources\Inventory\Reports;

// use Illuminate\Http\Request;
// use Illuminate\Http\Resources\Json\JsonResource;

// class SupplierReportResource extends JsonResource
// {
//     public function toArray(Request $request): array
//     {
//         $supplier = $this->resource['supplier'];

//         return [
//             'supplier' => [
//                 'id' => $supplier->id,
//                 'name' => $supplier->name,
//             ],

//             'total_purchases' =>
//                 $this->resource['total_purchases'],

//             'total_paid' =>
//                 $this->resource['total_paid'],

//             'outstanding' =>
//                 $this->resource['outstanding'],
//         ];
//     }
// }


namespace App\Http\Resources\Inventory\Reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total_purchases' =>
                (float) $this->resource['total_purchases'],

            'total_paid' =>
                (float) $this->resource['total_paid'],

            'outstanding' =>
                (float) $this->resource['outstanding'],
        ];
    }
}
