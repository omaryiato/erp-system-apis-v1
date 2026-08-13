<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollPeriodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'name' => $this->name,

            'period_start' => $this->period_start?->format('Y-m-d'),

            'period_end' => $this->period_end?->format('Y-m-d'),

            'status' => $this->status,

            'payslips_count' => $this->whenCounted(
                'payslips'
            ),

            'payslips' => PayslipResource::collection(
                $this->whenLoaded('payslips')
            ),

            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
