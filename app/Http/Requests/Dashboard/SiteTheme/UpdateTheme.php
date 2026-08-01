<?php

namespace App\Http\Requests\Dashboard\SiteTheme;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;


class UpdateTheme extends BaseRequest
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

            'theme_name' => [
                'required',
                'string',
                'max:100',
            ],

            'color_scheme' => [
                'nullable',
                'string',
                'max:255',
            ],

            'font_style' => [
                'nullable',
                'string',
                'max:100',
            ],

            'background_image' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'borders' => [
                'nullable',
                'string',
                'max:100',
            ],

            'status' => [
                'nullable',
                'integer',
                'in:0,1',
            ],

            'updated_by' => [
                'required',
                'integer',
                'exists:users,id'
            ],
        ];
    }
}
