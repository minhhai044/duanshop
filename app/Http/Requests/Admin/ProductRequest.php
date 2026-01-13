<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use App\Traits\ApiRequestJsonTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    use ApiRequestJsonTrait;

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
        if ($this->isMethod('post')) {
            return $this->rulesForCreate();
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            return $this->rulesForUpdate();
        }

        return [];
    }

    public function rulesForCreate()
    {
        return [
            'pro_name' => ['required', 'string', 'max:100', Rule::unique(Product::class)],
            'pro_sku' => ['required', 'string', Rule::unique(Product::class)],
            'pro_slug' => ['nullable', 'string', 'max:255', Rule::unique(Product::class)],
            'pro_description' => 'nullable|string',
            'pro_img_thumbnail' => 'nullable|image|max:2048',
            'pro_price_regular' => [
                'required',
                'numeric',
                'min:0',
                'max:999999999999999', // decimal(15,0)
            ],
            'pro_price_sale' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999999999999', // decimal(15,0)
            ],
            'pro_featured' => ['nullable', 'boolean'],
            'pro_views' => 'nullable|integer|min:0',
            'pro_prating' => 'nullable|numeric|min:0|max:10', // decimal(10,1)
            'is_hot' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'category_id' => 'required|exists:categories,id',

            'product_variants' => 'required|array',
            'product_variants.*.quantity' => 'nullable|integer|min:0',
            'product_variants.*.price' => 'required|integer|min:0',
            'product_variants.*.price_sale' => 'nullable|integer|min:0',

            'image_galleries' => 'required|array',
            'image_galleries.*' => 'required|image|max:2048',
        ];
    }

    public function rulesForUpdate()
    {
        $id = $this->route('product');
        return [
            'pro_name' => ['required', 'string', 'max:100', Rule::unique(Product::class)->ignore($id)],
            'pro_sku' => ['required', 'string', Rule::unique(Product::class)->ignore($id)],
            'pro_slug' => ['nullable', 'string', 'max:255', Rule::unique(Product::class)->ignore($id)],
            'pro_description' => 'nullable|string',
            'pro_img_thumbnail' => 'nullable|image|max:2048',
            'pro_price_regular' => [
                'required',
                'numeric',
                'min:0',
                'max:999999999999999', // decimal(15,0)
            ],
            'pro_price_sale' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999999999999', // decimal(15,0)
            ],
            'pro_featured' => ['nullable', 'boolean'],
            'pro_views' => 'nullable|integer|min:0',
            'pro_prating' => 'nullable|numeric|min:0|max:10', // decimal(10,1)
            'is_hot' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'category_id' => 'required|exists:categories,id',

            'product_variants' => 'required|array',
            'product_variants.*.quantity' => 'nullable|integer|min:0',
            'product_variants.*.price' => 'required|integer|min:0',
            'product_variants.*.price_sale' => 'nullable|integer|min:0',

            'image_galleries' => 'nullable|array',
            'image_galleries.*' => 'nullable|image|max:2048',
            'add_galleries' => 'nullable|array',
            'add_galleries.*' => 'nullable|image|max:2048',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Convert empty strings to null for nullable fields
        $input = $this->all();
        
        if (isset($input['pro_price_sale']) && $input['pro_price_sale'] === '') {
            $input['pro_price_sale'] = null;
        }
        
        if (isset($input['pro_prating']) && $input['pro_prating'] === '') {
            $input['pro_prating'] = null;
        }
        
        if (isset($input['pro_views']) && $input['pro_views'] === '') {
            $input['pro_views'] = null;
        }

        // Handle product variants
        if (isset($input['product_variants'])) {
            foreach ($input['product_variants'] as $key => $variant) {
                if (isset($variant['quantity']) && $variant['quantity'] === '') {
                    $input['product_variants'][$key]['quantity'] = null;
                }
                if (isset($variant['price_sale']) && $variant['price_sale'] === '') {
                    $input['product_variants'][$key]['price_sale'] = null;
                }
            }
        }
        
        $this->replace($input);
    }

    public function messages()
    {
        return [
            'pro_name.required' => 'Vui lòng nhập tên sản phẩm.',
            'pro_name.unique' => 'Tên sản phẩm đã tồn tại.',
            'pro_name.max' => 'Tên sản phẩm không được vượt quá 100 ký tự.',

            'pro_sku.required' => 'Vui lòng nhập mã SKU.',
            'pro_sku.unique' => 'Mã SKU đã tồn tại.',

            'pro_slug.unique' => 'Slug đã tồn tại.',
            'pro_slug.max' => 'Slug không được vượt quá 255 ký tự.',

            'pro_img_thumbnail.image' => 'Vui lòng chọn file hình ảnh.',
            'pro_img_thumbnail.max' => 'Hình ảnh không được vượt quá 2MB.',

            'pro_price_regular.required' => 'Vui lòng nhập giá gốc.',
            'pro_price_regular.numeric' => 'Giá gốc phải là số.',
            'pro_price_regular.min' => 'Giá gốc phải lớn hơn hoặc bằng 0.',
            'pro_price_regular.max' => 'Giá gốc quá lớn.',

            'pro_price_sale.numeric' => 'Giá khuyến mãi phải là số.',
            'pro_price_sale.min' => 'Giá khuyến mãi phải lớn hơn hoặc bằng 0.',
            'pro_price_sale.max' => 'Giá khuyến mãi quá lớn.',

            'pro_prating.numeric' => 'Đánh giá phải là số.',
            'pro_prating.min' => 'Đánh giá phải từ 0.',
            'pro_prating.max' => 'Đánh giá không được vượt quá 10.',

            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không tồn tại.',

            'product_variants.required' => 'Vui lòng thêm ít nhất một biến thể.',
            'product_variants.*.quantity.integer' => 'Số lượng phải là số nguyên.',
            'product_variants.*.quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 0.',
            'product_variants.*.price.required' => 'Vui lòng nhập giá cho biến thể.',
            'product_variants.*.price.integer' => 'Giá phải là số nguyên.',
            'product_variants.*.price.min' => 'Giá phải lớn hơn hoặc bằng 0.',
            'product_variants.*.price_sale.integer' => 'Giá khuyến mãi phải là số nguyên.',
            'product_variants.*.price_sale.min' => 'Giá khuyến mãi phải lớn hơn hoặc bằng 0.',

            'image_galleries.required' => 'Vui lòng chọn ít nhất một hình ảnh gallery.',
            'image_galleries.*.image' => 'File phải là hình ảnh.',
            'image_galleries.*.max' => 'Hình ảnh không được vượt quá 2MB.',
            'add_galleries.*.image' => 'File phải là hình ảnh.',
            'add_galleries.*.max' => 'Hình ảnh không được vượt quá 2MB.',
        ];
    }
}