<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;


class UpdateUser extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route("user");

        return [
            'full_name' => [
                'required',
                'string',
                'max:255',
            ],

            'user_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users_v1', 'user_name')
                        ->ignore($id)
                        ->where('id', $this->input('id')),
            ],

            'phone_number' => [
                'nullable',
                'string',
                'max:20',
            ],

            // 'email_address' => [
            //     'required',
            //     'email',
            //     'max:255',
            //     Rule::unique('users_v1', 'email_address')
            //             ->ignore($id)
            //             ->where('id', $this->input('id')),
            // ],

            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
            ],

            'status' => [
                'nullable',
                'integer',
                'in:0,1',
            ],

            'user_type' => [
                'nullable',
                'string',
                'in:employee,admin,manager,owner',
            ],

            'updated_by' => [
                'required',
                'integer',
                'exists:users_v1,id'
            ],
        ];
    }
}
