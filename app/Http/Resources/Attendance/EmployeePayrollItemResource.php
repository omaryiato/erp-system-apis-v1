<?php

namespace App\Http\Resources\Attendance;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



class EmployeePayrollItemResource extends JsonResource
{


public function toArray(Request $request): array
{


    return [


        'id' => $this->id,

        'employee' => [

            'id' => $this->employee?->id,

            'full_name' => $this->employee?->full_name,

            'employee_id_number' => $this->employee?->employee_id_number

        ],


        'item'=>[

            'id' => $this->payrollItemType?->id,

            'name' => $this->payrollItemType?->name,

            'item_kind' => $this->payrollItemType?->item_kind,

        ],


        'value' => $this->value,

        'effective_date' => $this->effective_from?->format('Y-m-d'),

        // 'effective_to'=>
        // $this->effective_to?->format('Y-m-d'),

    ];


    }


}
