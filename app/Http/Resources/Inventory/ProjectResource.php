<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $totalRevenue = (float) $this->revenues->sum('amount');

        $paidAmount = (float) $this->cashTransactions->sum('amount');

        return [
            'id' => $this->id,

            'project_code' => $this->project_code,

            'project_name' => $this->project_name,

            'customer' => [
                'name' => $this->customer_name,
                'phone' => $this->phone,
                'email' => $this->email,
            ],

            'address' => $this->address,

            'description' => $this->description,

            'start_date' => $this->start_date,

            'end_date' => $this->end_date,

            'status' => $this->status,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,

            'total_amount' => $totalRevenue,

            'paid_amount' => $paidAmount,

            'remaining_amount' => max( $totalRevenue - $paidAmount, 0 ),

            'revenues' => RevenueResource::collection($this->whenLoaded('revenues')),

            'cash_transactions' => CashTransactionResource::collection($this->whenLoaded('cashTransactions'))
        ];
    }
}
