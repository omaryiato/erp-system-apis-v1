<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $paidAmount = $this->relationLoaded(
            'cashTransactions'
        )
            ? $this->cashTransactions->sum('amount')
            : null;

        return [
            'id' => $this->id,

            'expense_number' =>
                $this->expense_number,

            'project' => [
                'id' => $this->project?->id,
                'name' => $this->project?->project_name,
            ],

            'supplier' => [
                'id' => $this->supplier?->id,
                'name' => $this->supplier?->name,
            ],

            'expense_date' =>
                $this->expense_date,

            'category' =>
                $this->category,

            'description' =>
                $this->description,

            'amount' =>
                $this->amount,

            'paid_amount' =>
                $paidAmount,

            'remaining_amount' =>
                $paidAmount !== null
                    ? (float) $this->amount - $paidAmount
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
