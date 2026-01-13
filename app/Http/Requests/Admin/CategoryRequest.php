<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use App\Traits\ApiRequestJsonTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
            'cate_name' => ['required', 'string', 'max:255', Rule::unique(Category::class)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique(Category::class)],
            'cate_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_active' => ['nullable', 'boolean']
        ];
    }

    public function rulesForUpdate()
    {
        $id = $this->route('category');
        return [
            'cate_name' => ['required', 'string', 'max:255', Rule::unique(Category::class)->ignore($id)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique(Category::class)->ignore($id)],
            'cate_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_active' => ['nullable', 'boolean']
        ];
    }

    public function messages()
    {
        return [
            'cate_name.required' => 'Vui lòng nhập tên danh mục.',
            'cate_name.unique' => 'Tên danh mục đã tồn tại.',
            'cate_name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug đã tồn tại.',
            'slug.max' => 'Slug không được vượt quá 255 ký tự.',
            'cate_image.image' => 'Vui lòng chọn file hình ảnh.',
            'cate_image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'cate_image.max' => 'Hình ảnh không được vượt quá 2MB.',
        ];
    }
}