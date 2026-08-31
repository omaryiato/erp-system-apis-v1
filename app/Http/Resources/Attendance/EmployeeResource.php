<?php

namespace App\Http\Resources\Attendance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_number' => $this->employee_number,
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'salary_type' => $this->salary_type,
            'base_salary' => $this->base_salary,
            'overtime_rate' => $this->overtime_rate,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
