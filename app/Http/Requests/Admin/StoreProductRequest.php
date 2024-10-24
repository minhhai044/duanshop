<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pro_name' => ['required', 'max:100', Rule::unique(Product::class)],
            'pro_sku' => ['required', Rule::unique(Product::class)],
            'pro_description' => 'nullable',
            'pro_img_thumbnail' => 'nullable|image|max:2048',
            'pro_price_regular' =>  [
                'required',
                'numeric',     // Đảm bảo rằng đây là số (bao gồm số thập phân)
                'min:0',       // Giá trị tối thiểu
                'max:99999999.99', // Giá trị tối đa, tương ứng với decimal(10,2)
            ],
            'pro_price_sale' =>  [
                'nullable',
                'numeric',
                'min:0',       // Giá trị tối thiểu
                'max:99999999.99',
            ],
            'pro_featured' => ['nullable', Rule::in('0', '1')],
            'pro_views' => 'nullable',
            'category_id' => 'required',

            'product_variants' => 'required|array',
            'product_variants.*.quantity' => 'required|integer|min:0',

            'tags' => 'required|array',
            'tags.*' => 'required|integer',

            'image_galleries'     => 'required|array',
            'image_galleries.*'   => 'required|image',
        ];
    }
    public function messages()
    {
        return [
            'pro_name.required' => "Vui lòng nhập Name",
            'pro_name.unique' => "Name đã tồn tại",

            'pro_sku.required' => "Vui lòng nhập Sku",
            'pro_sku.unique' => "Sku đã tồn tại",

            'pro_img_thumbnail.image' => "Vui lòng chọn lại Image",
            'pro_img_thumbnail.max' => "Image không được vượt quá 2MB",

            'pro_price_regular.required' => "Vui lòng nhập Price Regular",
            'pro_price_regular.numeric' => "Vui lòng nhập Price numeric",
            'pro_price_regular.min' => "Price numeric phải lớn hơn 0",
            'pro_price_regular.max' => "Price numeric quá lớn",

            'pro_price_sale.numeric' => "Vui lòng nhập Price numeric",
            'pro_price_sale.min' => "Price numeric phải lớn hơn 0",
            'pro_price_sale.max' => "Price numeric quá lớn",

            'category_id'  => "Vui lòng chọn Category",

            'product_variants.*.quantity.required' => "Vui lòng nhập Quantity cho biến thể",
            'product_variants.*.quantity.integer' => "Vui lòng không để số 0 đầu tiên trong Quantity",
            'product_variants.*.quantity.min' => "Quantity phải lớn hơn hoặc bằng 0",

            'tags.required' => "Vui lòng chọn Tag",

            'image_galleries.required' => "Vui lòng chọn Gallery Image",
            'image_galleries.image' => "Vui lòng chọn lại Gallery phải là Image",
        ];
    }
}
