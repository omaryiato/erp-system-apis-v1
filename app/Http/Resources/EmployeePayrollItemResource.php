<?php

namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



class EmployeePayrollItemResource extends JsonResource
{


public function toArray(Request $request): array
{


return [


'id'=>$this->id,



'employee'=>[

'id'=>$this->employee?->id,

'name'=>$this->employee?->full_name,

'employee_number'=>
$this->employee?->employee_id_number

],




'item'=>[

'id'=>$this->payrollItemType?->id,

'name'=>$this->payrollItemType?->name,


'type'=>$this->payrollItemType?->item_kind,


'is_percentage'=>
$this->payrollItemType?->is_percentage

],




'value'=>$this->value,



'effective_from'=>
$this->effective_from?->format('Y-m-d'),



'effective_to'=>
$this->effective_to?->format('Y-m-d'),



'created_at'=>$this->created_at


];


}


}
