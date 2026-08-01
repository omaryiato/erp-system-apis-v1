<?php

namespace App\Http\Requests\HR\EmployeePayrollItem;


use Illuminate\Foundation\Http\FormRequest;



class AddNewEmployeePayrollItem extends FormRequest
{


public function authorize()
{
    return true;
}




public function rules()
{


return [


'employee_id'=>
'required|exists:employees,id',



'payroll_item_type_id'=>
'required|exists:payroll_item_types,id',




'value'=>
'required|numeric|min:0',




'effective_from'=>
'required|date',




'effective_to'=>
'nullable|date|after_or_equal:effective_from'


];


}



}
