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


        'name' => [
            'bail',
            'required',
            'string',
            'max:100',
        ],

        'item_kind' => [
            'bail',
            'required',
            'in:allowance,deduction',
        ],

        'is_percentage' => [
            'bail',
            'boolean',
        ],

        'default_value' => [
            'bail',
            'numeric',
            'min:0',
        ],

        'is_active' => [
            'bail',
            'boolean',
        ],

    ];


}


}
