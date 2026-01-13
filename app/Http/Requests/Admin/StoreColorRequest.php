<?php

namespace App\Http\Requests\Admin;

use App\Models\Color;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreColorRequest extends FormRequest
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
            'color_name' => ['required', 'string', 'max:100', Rule::unique(Color::class)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique(Color::class)],
            'is_active' => ['nullable', 'boolean']
        ];
    }
}
