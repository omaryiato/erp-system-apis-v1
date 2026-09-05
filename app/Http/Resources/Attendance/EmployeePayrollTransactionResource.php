<?php

namespace App\Http\Resources\Attendance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeePayrollTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'employee_id' => $this->employee_id,

            'employee' => $this->whenLoaded(
                'employee',
                fn () => [
                    'id' => $this->employee->id,
                    'employee_id_number' =>
                        $this->employee->employee_id_number,
                    'full_name' =>
                        $this->employee->full_name,
                ]
            ),

            'type' => $this->type,

            'amount' => $this->amount,

            'remaining_amount' =>
                $this->remaining_amount,

            'installment_amount' =>
                $this->installment_amount,

            'transaction_date' =>
                $this->transaction_date?->format('Y-m-d'),

            'description' => $this->description,

            'status' => $this->status,

            'created_at' =>
                $this->created_at?->format(
                'Y-m-d H:i:s'
            ),

        ];
    }
}
