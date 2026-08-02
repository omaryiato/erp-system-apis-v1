<?php

namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



class AttendanceResource extends JsonResource
{


public function toArray(Request $request): array
{


    return [

        'id'=>$this->id,


        'employee' => [

            'id' => $this->employee?->id,

            'employee_id_number' => $this->employee?->employee_id_number,

            'full_name' => $this->employee?->full_name

        ],


        'work_date' => $this->work_date?->format('Y-m-d'),

        'check_in' => $this->check_in?->format('Y-m-d H:i:s'),

        'check_out' => $this->check_out?->format('Y-m-d H:i:s'),

        'source' => $this->source,

        'status' => $this->status,

        // 'late_minutes' => $this->late_minutes,

        'notes' => $this->notes,


        'created_at' => $this->created_at?->format('Y-m-d H:i:s'),


    ];


}


}
