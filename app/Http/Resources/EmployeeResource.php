<?php

namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



class EmployeeResource extends JsonResource
{


public function toArray(Request $request): array
{
    return [

        'id' => $this->id,

        'employee_id_number' => $this->employee_id_number,

        'full_name' => $this->full_name,

        'national_id' => $this->national_id,

        'department' => [
            'id' => $this->department?->id,
            'name' => $this->department?->name
        ],

        'position' => [
            'id' => $this->position?->id,
            'name' => $this->position?->name
        ],

        'hire_date' => $this->hire_date?->format('Y-m-d'),
        'termination_date' => $this->termination_date?->format('Y-m-d'),

        'status' => $this->status,

        'base_salary' => $this->base_salary,

        // 'shift_id' => $this->shift_id,
        // 'biometric_code' => $this->biometric_code,

        'created_at'  => $this->created_at?->format('Y-m-d H:i:s'),
        'updated_at'  => $this->updated_at?->format('Y-m-d H:i:s'),

    ];
}

}
