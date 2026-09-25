<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinancialAccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'name' => $this->name,
            'name_ar' => $this->name_ar,

            'account_type' => $this->account_type,

            'account_number' => $this->account_number,
            'iban' => $this->iban,
            'bank_name' => $this->bank_name,

            'currency' => $this->currency,

            'opening_balance' => $this->opening_balance,

            'is_active' => $this->is_active,

            'description' => $this->description,

            'cheques' => ChequeResource::collection($this->whenLoaded('cheques')),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
