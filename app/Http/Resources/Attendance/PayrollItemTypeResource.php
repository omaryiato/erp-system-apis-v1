<?php

namespace App\Http\Resources\Attendance;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



class PayrollItemTypeResource extends JsonResource
{


public function toArray(Request $request): array
{


return [


'id'=>$this->id,


'company'=>[

'id'=>$this->company?->id,

'name'=>$this->company?->name

],



'name'=>$this->name,



'type'=>$this->item_kind,



'is_percentage'=>
$this->is_percentage,



'default_value'=>
$this->default_value,



'is_active'=>
$this->is_active,



'created_at'=>
$this->created_at


];


}


}
