<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChequeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'cheque_number' => $this->cheque_number,

            'cheque_type' => $this->cheque_type,

            'financial_account_id' =>
                $this->financial_account_id,

            'financial_account' =>
                new FinancialAccountResource(
                    $this->whenLoaded('financialAccount')
                ),

            'party_type' => $this->party_type,

            'party_id' => $this->party_id,

            'amount' => $this->amount,

            'issue_date' => $this->issue_date?->format('Y-m-d'),

            'due_date' => $this->due_date?->format('Y-m-d'),

            'status' => $this->status,

            'description' => $this->description,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
