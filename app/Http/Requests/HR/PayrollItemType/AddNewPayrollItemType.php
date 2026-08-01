<?php

namespace App\Http\Requests\HR\PayrollItemType;


use Illuminate\Foundation\Http\FormRequest;



class AddNewPayrollItemType extends FormRequest
{


public function authorize()
{
    return true;
}





public function rules()
{


return [


'company_id'=>
'required|exists:companies,id',



'name'=>
'required|string|max:100',




'item_kind'=>
'required|in:allowance,deduction',




'is_percentage'=>
'boolean',




'default_value'=>
'numeric|min:0',




'is_active'=>
'boolean'


];


}


}
