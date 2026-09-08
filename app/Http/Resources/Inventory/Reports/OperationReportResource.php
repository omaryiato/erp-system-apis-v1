<?php

namespace App\Http\Resources\Inventory\Reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'revenues' => [
                'total' => $this->total_revenues,
                'received' => $this->received_revenues,
                'remaining' => $this->remaining_revenues,
            ],

            'expenses' => [
                'total' => $this->total_expenses,
                'paid' => $this->paid_expenses,
                'remaining' => $this->remaining_expenses,
            ],

            'purchases' => [
                'total' => $this->total_purchases ,
            ],
        ];
    }
}
