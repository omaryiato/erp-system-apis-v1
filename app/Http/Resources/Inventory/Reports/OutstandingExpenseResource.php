<?php

namespace App\Http\Resources\Inventory\Reports;

use App\Http\Resources\Inventory\ExpenseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OutstandingExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'expense' =>
                new ExpenseResource(
                    $this->resource['expense']
                ),

            'amount' =>
                $this->resource['amount'],

            'paid_amount' =>
                $this->resource['paid_amount'],

            'remaining_amount' =>
                $this->resource['remaining_amount'],
        ];
    }
}
