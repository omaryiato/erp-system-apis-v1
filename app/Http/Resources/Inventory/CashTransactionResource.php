<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CashTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' =>
                $this->id,

            'transaction_number' =>
                $this->transaction_number,

            'transaction_type' =>
                $this->transaction_type,

            'amount' =>
                $this->amount,

            'transaction_date' =>
                $this->transaction_date,

            'project' => [
                'id' => $this->project?->id,
                'name' => $this->project?->project_name,
            ],

            'supplier' => [
                'id' => $this->supplier?->id,
                'name' => $this->supplier?->name,
            ],

            'expense_id' =>
                $this->expense_id,

            'revenue_id' =>
                $this->revenue_id,

            'employee_payment_id' =>
                $this->employee_payment_id,

            'purchase_order_id' =>
                $this->purchase_order_id,

            'description' =>
                $this->description,

            'notes' =>
                $this->notes,

            'created_at' =>
                $this->created_at,

            'updated_at' =>
                $this->updated_at,
        ];
    }
}
