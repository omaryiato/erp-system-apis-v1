<?php

namespace App\Http\Requests\HR\EmployeePayrollItem;


use Illuminate\Foundation\Http\FormRequest;



class UpdateEmployeePayrollItem extends FormRequest
{


public function authorize()
{
    return true;
}




public function rules()
{


return [


'value'=>
'sometimes|numeric|min:0',



'effective_from'=>
'sometimes|date',




'effective_to'=>
'nullable|date|after_or_equal:effective_from'


];


}


}
