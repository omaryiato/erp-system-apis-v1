<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeePaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'payment_date' =>
                $this->payment_date?->format('Y-m-d'),
            'amount' => $this->amount,
            'payment_type' => $this->payment_type,
            'period_start' =>
                $this->period_start?->format('Y-m-d'),
            'period_end' =>
                $this->period_end?->format('Y-m-d'),
            'notes' => $this->notes,
        ];
    }
}
