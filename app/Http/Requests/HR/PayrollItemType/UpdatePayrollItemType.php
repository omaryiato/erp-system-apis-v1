<?php

namespace App\Http\Requests\HR\PayrollItemType;


use Illuminate\Foundation\Http\FormRequest;



class UpdatePayrollItemType extends FormRequest
{


public function authorize()
{
    return true;
}




public function rules()
{


return [


'name'=>
'sometimes|string|max:100',




'item_kind'=>
'sometimes|in:allowance,deduction',




'is_percentage'=>
'sometimes|boolean',




'default_value'=>
'sometimes|numeric|min:0',




'is_active'=>
'sometimes|boolean'


];


}


}
