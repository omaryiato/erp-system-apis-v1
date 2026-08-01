<?php

namespace App\Http\Requests\Dashboard\Product;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;



class UpdateProduct extends BaseRequest
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
        $id = $this->route("product");

        return [

            // Product
            'product_en_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'product_en_name')
                        ->ignore($id)
                        ->where('id', $this->input('id')),
            ],

            'product_ar_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'product_ar_name')
                        ->ignore($id)
                        ->where('id', $this->input('id')),
            ],

            'product_en_description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'product_ar_description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'product_material' => [
                'nullable',
                'integer',
                'exists:lookup_values,id',
            ],

            'product_stone' => [
                'nullable',
                'integer',
                'exists:lookup_values,id',
            ],

            'product_reels' => [
                'nullable',
                'file',
                'mimes:mp4',
                'max:50000',
            ],

            'product_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'product_status' => [
                'nullable',
                'integer',
                'in:0,1',
            ],

            'category_id' => [
                'required',
                'integer',
                'exists:category,id',
            ],


            // Images
            'product_images' => [
                'nullable',
                'array',
            ],

            'product_images.*.image' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'product_images.*.is_primary' => [
                'nullable',
                'integer',
                'in:0,1',
            ],

            'product_images.*.sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],


            // Variants
            'product_variants' => [
                'nullable',
                'array',
            ],

            'product_variants.*.color_id' => [
                'nullable',
                'integer',
                'exists:lookup_values,id',
            ],

            'product_variants.*.size_id' => [
                'nullable',
                'integer',
                'exists:lookup_values,id',
            ],

            'product_variants.*.sku' => [
                'nullable',
                'string',
                'max:100',
                'distinct',
                'unique:product_variants,sku',
                // Rule::unique('product_variants', 'sku')
                //         ->ignore($id)
                //         ->where('id', $this->input('id')),
            ],

            'product_variants.*.stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'product_variants.*.price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'product_variants.*.status' => [
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
