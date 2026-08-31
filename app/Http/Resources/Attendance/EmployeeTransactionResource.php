<?php

namespace App\Http\Resources\Attendance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'type' => $this->type,
            'amount' => $this->amount,
            'transaction_date' =>
                $this->transaction_date?->format('Y-m-d'),
            'description' => $this->description,
        ];
    }
}
