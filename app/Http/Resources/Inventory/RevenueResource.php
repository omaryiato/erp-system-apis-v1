<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RevenueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $receivedAmount = $this->relationLoaded(
            'cashTransactions'
        )
            ? $this->cashTransactions->sum('amount')
            : null;

        return [
            'id' =>
                $this->id,

            'revenue_number' =>
                $this->revenue_number,

            'project' => [
                'id' => $this->project?->id,
                'name' => $this->project?->project_name,
            ],

            'revenue_date' =>
                $this->revenue_date,

            'category' =>
                $this->category,

            'description' =>
                $this->description,

            'amount' =>
                $this->amount,

            'received_amount' =>
                $receivedAmount,

            'remaining_amount' =>
                $receivedAmount !== null
                    ? (float) $this->amount - $receivedAmount
                    : null,

            'notes' =>
                $this->notes,

            'created_at' =>
                $this->created_at,

            'updated_at' =>
                $this->updated_at,
        ];
    }
}
