<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $totalPurchase = (float) $this->purchase?->items->sum('total_amount');

        $paidAmount = (float) $this->cashTransactions->sum('amount');

        return [
            'id' => $this->id,

            'supplier_code' => $this->supplier_code,

            'name' => $this->name,

            'phone' => $this->phone,

            'email' => $this->email,

            'address' => $this->address,

            'tax_number' => $this->tax_number,

            'notes' => $this->notes,

            'status' => $this->status,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,

            'total_amount' => $totalPurchase,

            'paid_amount' => $paidAmount,

            'remaining_amount' => max( $totalPurchase - $paidAmount, 0 ),

            'purchase' => PurchaseResource::collection($this->whenLoaded('purchases')),

            'cash_transactions' => CashTransactionResource::collection($this->whenLoaded('cashTransactions'))
        ];
    }
}
