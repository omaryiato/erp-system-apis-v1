<?php

namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



class EmployeeResource extends JsonResource
{


public function toArray(Request $request): array
{
    return [

    'id'=>$this->id,

    'employee_number'
    =>$this->employee_id_number,


    'name'
    =>$this->full_name,


    'national_id'
    =>$this->national_id,


    'department'=>[
        'id'=>$this->department?->id,
        'name'=>$this->department?->name
    ],


    'position'=>[
        'id'=>$this->position?->id,
        'name'=>$this->position?->name
    ],


    'hire_date'
    =>$this->hire_date?->format('Y-m-d'),


    'status'
    =>$this->status,


    'salary'
    =>$this->base_salary,


    'created_at'
    =>$this->created_at


    ];
}

}
