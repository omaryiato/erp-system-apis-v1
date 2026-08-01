<?php

namespace App\Http\Requests\HR\Attendance;


use Illuminate\Foundation\Http\FormRequest;



class AddNewAttendance extends FormRequest
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


'work_date'=>
'required|date',



'check_in'=>
'nullable|date',



'check_out'=>
'nullable|date|after_or_equal:check_in',




'source'=>
'required|in:manual,device,mobile,system',




'status'=>
'required|in:present,absent,late,half_day,on_leave',




'late_minutes'=>
'nullable|integer|min:0',



'notes'=>
'nullable|string',



'created_by'=>
'nullable|integer'

];


}


}
