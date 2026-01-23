<?php

namespace App\Http\Requests\Admin;

use App\Models\Color;
use App\Traits\ApiRequestJsonTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ColorRequest extends FormRequest
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
            'color_name' => ['required', 'string', 'max:100', Rule::unique(Color::class)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique(Color::class)],
            'is_active' => ['nullable', 'boolean'],
            'color_code' => ['nullable', 'string', 'max:7', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'color_text' => ['nullable', 'string', 'max:7', 'regex:/^#[0-9A-Fa-f]{6}$/']
        ];
    }

    public function rulesForUpdate()
    {
        $id = $this->route('color');
        return [
            'color_name' => ['required', 'string', 'max:100', Rule::unique(Color::class)->ignore($id)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique(Color::class)->ignore($id)],
            'is_active' => ['nullable', 'boolean'],
            'color_code' => ['nullable', 'string', 'max:7', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'color_text' => ['nullable', 'string', 'max:7', 'regex:/^#[0-9A-Fa-f]{6}$/']
        ];
    }

    public function messages()
    {
        return [
            'color_name.required' => 'Vui lòng nhập tên màu sắc.',
            'color_name.unique' => 'Tên màu sắc đã tồn tại.',
            'color_name.max' => 'Tên màu sắc không được vượt quá 100 ký tự.',
            'slug.unique' => 'Slug đã tồn tại.',
            'slug.max' => 'Slug không được vượt quá 255 ký tự.',
            'color_code.regex' => 'Mã màu phải có định dạng hex hợp lệ (ví dụ: #ffffff).',
            'color_code.max' => 'Mã màu không được vượt quá 7 ký tự.',
            'color_text.regex' => 'Màu chữ phải có định dạng hex hợp lệ (ví dụ: #000000).',
            'color_text.max' => 'Màu chữ không được vượt quá 7 ký tự.',
        ];
    }
}