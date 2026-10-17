<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FullReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'revenues' => [
                'total' => $this['total_revenues'],
                'received' => $this['received_revenues'],
                'remaining' => $this['remaining_revenues'],
            ],

            'expenses' => [
                'total' => $this['total_purchases'],
                'paid' => $this['paid_expenses'],
                'remaining' => $this['remaining_expenses'],
            ],

            'purchases' => [
                'total' => $this['total_expenses'],
                'paid' => $this['paid_purchases'],
                'remaining' => $this['remaining_purchases'],
            ],

            'employees' => [
                'total' => $this['total_employee_expenses'],
                'paid' => $this['paid_employee_expenses'],
                'remaining' => $this['remaining_employee_expenses'],
            ],


            'assets' => [
                'total' => $this['total_asset_expenses'],
                'paid' => $this['paid_asset_expenses'],
                'remaining' => $this['remaining_asset_expenses'],
            ],
        ];
    }
}
