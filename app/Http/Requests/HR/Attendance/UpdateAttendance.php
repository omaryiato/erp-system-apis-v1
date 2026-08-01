<?php

namespace App\Http\Requests\HR\Attendance;


use Illuminate\Foundation\Http\FormRequest;



class UpdateAttendance extends FormRequest
{


public function authorize()
{
    return true;
}




public function rules()
{

return [


'check_in'=>
'nullable|date',



'check_out'=>
'nullable|date|after_or_equal:check_in',




'source'=>
'nullable|in:manual,device,mobile,system',




'status'=>
'nullable|in:present,absent,late,half_day,on_leave',




'late_minutes'=>
'nullable|integer|min:0',



'notes'=>
'nullable|string'


];


}



}
