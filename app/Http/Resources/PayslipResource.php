<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayslipResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'payroll_period_id' => $this->payroll_period_id,

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

            'base_salary' => $this->base_salary,

            'total_allowances' => $this->total_allowances,

            'total_deductions' => $this->total_deductions,

            'net_salary' => $this->net_salary,

            'status' => $this->status,

            'generated_at' =>
                $this->generated_at?->toISOString(),

            'items' => PayslipItemResource::collection(
                $this->whenLoaded('items')
            ),
        ];
    }
}
