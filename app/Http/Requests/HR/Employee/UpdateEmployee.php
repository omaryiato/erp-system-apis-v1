<?php

namespace App\Http\Requests\HR\Employee;


use Illuminate\Foundation\Http\FormRequest;



class UpdateEmployee extends FormRequest
{

public function authorize()
{
    return true;
}



public function rules()
{


return [

'employee_id_number'
=>'sometimes|string|max:30|unique:employees,employee_id_number,'.$this->employee,


'full_name'
=>'sometimes|string|max:200',


'national_id'
=>'nullable|string|max:50',


'department_id'
=>'nullable|exists:departments,id',


'position_id'
=>'nullable|exists:positions,id',


'hire_date'
=>'nullable|date',


'termination_date'
=>'nullable|date',


'status'
=>'nullable|in:active,suspended,terminated,on_leave',


'base_salary'
=>'nullable|numeric|min:0',


'biometric_code'
=>'nullable|string|max:50'


];


}


}
