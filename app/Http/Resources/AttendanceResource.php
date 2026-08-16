<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'work_date' => $this->work_date?->format('Y-m-d'),

            'check_in' => $this->check_in?->format(
                'Y-m-d H:i:s'
            ),

            'check_out' => $this->check_out?->format(
                'Y-m-d H:i:s'
            ),

            'worked_hours' => $this->worked_hours,
            'overtime_hours' => $this->overtime_hours,
            'daily_amount' => $this->daily_amount,
            'overtime_amount' => $this->overtime_amount,
            'notes' => $this->notes,
        ];
    }
}
