<?php

namespace App\Http\Requests\Login;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class Login extends BaseRequest
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
        return [
            // 'email' => 'required|email|exists:users_v1,email',
            // 'user_name' => 'required|email|exists:users_v1,email',
            // 'phone_number' => 'required|email|exists:users_v1,email',
            'password' => [
                'required',
                'string',
            ],
            
            'login' => [
                'required',
                'string',
            ],
        ];
    }
}
