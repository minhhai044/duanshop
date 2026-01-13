<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
            'cate_name' => ['required', 'string', 'max:50', Rule::unique(Category::class)->ignore($this->route('category'))],
            'cate_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique(Category::class)->ignore($this->route('category'))],
            'is_active' => ['nullable', 'boolean']
        ];
    }
}
